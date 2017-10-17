<?php

/*
 * Copyright (C) 2017 chrisdoherty
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Backup\Commands;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Cilex\Provider\Console\Command;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;
use \PHLAK\Config\Config;

/**
 * @class CPanelBackupCommand
 * Backs up a website via the CPanel full website backup feature. The backup
 * is pushed to an FTP server once complete. All CPanel and FTP configuration
 * is achieved through the Dotenv file located at a user defined location. 
 * 
 * The command expects a single argument, the Dotenv directory path. 
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CPanelBackupCommand extends Command
{    
    /**
     * The guzzle client used to log in to the CPanel interface.
     * 
     * @var \GuzzlHttp\Client
     */
    private $httpClient;
    
    /**
     *
     * @var Config Object
     */
    private $cpanelConfig;
    
    /**
     * The path of the website we're interfacing with. This is gathered from
     * the response of our login request.
     * 
     * @var string
     */
    private $path;
    
    /**
     * Configures the command object.
     * 
     * @return void
     */
    public function configure() 
    {
        $this->setName('cpanel')
            ->setDescription('A complete backup via cPanel that stashes the'
                . ' backup in the home directory.');
    }
    
    /**
     * Loads the cpanel backup environment before logging in and making a 
     * backup request. The backup request is only sent to an FTP server 
     * defined in the environment configuration. 
     * 
     * @param InputInterface $input Console input interface.
     * @param OutputInterface $output Console output interface.
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Logging in to CPanel interface.</>");
        
        $response = $this->login();
        $statusCode = $response->getStatusCode();
        
        if ($statusCode >= 200 && $statusCode < 400) {
            $output->writeln("<info>Successfully logged in.</>");
            
            $this->path = $this->extractLoginResponsePath($response);
            
            $output->writeln("<info>Requesting backup to home directory.</>");
            
            if ($this->createBackup()->getStatusCode() == 200) {
                $output->writeln("<info>Successfully requested backup.</>");
            } else {
                $output->writeln("<error>There was an error requesting a "
                        . "backup.</>");
            }
        } else {
            $output->writeln("<error>Failed to log in to CPanel. Check the "
                    . "supplied credentials in the .env.cpanelbackup file.</>");
        }
    }
    
    /**
     * Sets the http client.
     * 
     * @param HttpClient $client
     * @return void
     */
    public function setHttpClient(HttpClient $client) 
    {
        $this->httpClient = $client;
    }
    
    /**
     * Sets the cPanel configuration.
     * 
     * @param \PHLAX\Config\Config $config
     * @return void
     */
    public function setCPanelConfig(Config $config) 
    {
        $this->cpanelConfig = $config;
    }
    
    /**
     * Logs in to the CPanel interface using the http client and defined 
     * environment variables.
     * 
     * @return \GuzzleHttp\Response
     */
    private function login()
    {
        return $this->httpClient->request('POST', '/login', [
            'form_params' => [
                'user' => $this->cpanelConfig->get('username'),
                'pass' => $this->cpanelConfig->get('password'),
                'goto_uri' => '/'
            ],
            'debug' => $this->cpanelConfig->get('debug')
        ]);
    }
    
    /**
     * Submits the CPanel backup request and asks CPanel to push the backup
     * to an FTP server.
     * 
     * @return \GuzzleHttp\Response
     */
    private function createBackup()
    {
        return $this->httpClient->request(
            'POST', 
            $this->createPath('backup/wizard-dofullbackup.html'),
            [
                'form_params' => [
                    'dest' => 'homedir',
                    'email_radio' => 0
                ],
                'debug' => $this->cpanelConfig->get('debug')
            ]);
    }
    
    /**
     * Retrieves a full path including the extension retrieved from the initial
     * response when we logged into CPanel.
     * 
     * @param string $extension
     * @return string
     */
    private function createPath($extension)
    {
        return sprintf("%s/%s", $this->path, $extension);
    }
    
    /**
     * This function expects the initial response from a CPanel login as the 
     * redirect or location will merely be to the index.php page. This function
     * extracts the path excluding the index.php segment so we can use it when
     * submitting a request for backup. 
     * 
     * @param HttpResponse $response The http response received from a CPanel 
     *  login.
     * @return string
     */
    private function extractLoginResponsePath(HttpResponse $response) 
    {
        $path = (new \Purl\Url($response->getHeader('Location')[0]))->path;
        return substr($path, 0, strrpos($path, "/"));
    }
}

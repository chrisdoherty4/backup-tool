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

namespace Backup\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use Dotenv\Dotenv;

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
     * The name of the argument for the environment path.
     */
    const ENV = "env_path";
    
    /**
     * The guzzle client used to log in to the CPanel interface.
     * 
     * @var \GuzzlHttp\Client
     */
    private $httpClient;
    
    /**
     * Configures the command object.
     * 
     * @return void
     */
    public function configure() 
    {
        $this->setName('backup:cpanel')
                ->setDescription('A complete backup via cPanel')
                ->addArgument(self::ENV, InputArgument::REQUIRED, 
                        'Path to the dotenv file');
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
        $output->writeln("<info>Loading environment.</info>");
        
        try {
            $this->loadEnvironment($input);
        }
        catch(RunetimeException $e) {
            $output->writeln("<error>Could not load environment variables.</>");
        }
        
        $output->writeln("<info>Logging in to CPanel interface.</>");
        
        // Log in to the CPanel interface.
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => getenv('CPANEL_HOST'),
            'cookies' => true,
            'allow_redirects' => false
        ]);
        
        $response = $this->loginCpanel();
        
        if ($response->getStatusCode() == 200) {
            $url = new \Purl\Url($response->getHeader('Location')[0]);
            
            echo $url;
        }
    }
    
    /**
     * Logs in to the CPanel interface using the http client and defined 
     * environment variables.
     * 
     * @return \GuzzleHttp\Response
     */
    private function loginCpanel()
    {
        return $this->httpClient->request('POST', '/login', [
            'form_params' => [
                'user' => getenv('CPANEL_USER'),
                'pass' => getenv('CPANEL_PASS'),
                'goto_uri' => '/'
            ],
            'debug' => true
        ]);
    }
    
    /**
     * Submits the CPanel backup request and asks CPanel to push te backup
     * to an FTP server.
     * 
     * @return \GuzzleHttp\Response
     */
    private function submitBackupRequest()
    {
        
    }
    
    /**
     * Loads the envionrment from the .env file defined for cpanel backup.
     * 
     * @param InputInterface $input The console Input interface.
     * @return void
     */
    private function loadEnvironment(InputInterface $input)
    {
        $env = new Dotenv($input->getArgument(self::ENV), 'env.cpanelbackup');
        
        $env->required('CPANEL_HOST')->notEmpty();
        $env->required('CPANEL_USER')->notEmpty();
        $env->required('CPANEL_PASS');
        $env->required('FTP_HOST')->notEmpty();
        $env->required('FTP_USER')->notEmpty();
        $env->required('FTP_PASS');
    }
}

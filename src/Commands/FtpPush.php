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
use \Symfony\Component\Console\Input\InputArgument;
use \Cilex\Provider\Console\Command;
use \PHLAK\Config\Config;
use \FtpClient\FtpClient;
use \FtpClient\FtpException;

/**
 * @class FtpPushCommand
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FtpPush extends Command
{
    /**
     * The target FTP Server configuration.
     * 
     * @var \PHLAK\Config\Config
     */
    private $config;

    /**
     * An instance of an FtpClient that we can use to connect to the FTP
     * server.
     * 
     * @var \FtpClient\FtpClient
     */
    private $client;

    /**
     * Initialises command with appropriate dependencies.
     * 
     * @param Config $config
     * @param FtpClient $client
     */
    public function __construct(Config $config, FtpClient $client)
    {
        parent::__construct();
        
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Configures the command attributes.
     */
    public function configure()
    {
        $this->setName("ftppush")
            ->setDescription("Push a backup to an FTP Server.")
            ->addArgument('backup_path', InputArgument::REQUIRED, 'The fully '
                . 'qualified backup path. The filename can be in regex '
                . 'format.');
    }

    /**
     * Using a provided path to the backup file, takes the backup file and
     * pushes to an FTP server.
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException Thrown if the command is uninitialised.
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("==================================================");
        $output->writeln("FTP Push Command");
        $output->writeln("==================================================");
        
        $path = $input->getArgument('backup_path');
        
        $backups = $this->getBackups($path);
        
        $total = count($backups);

        $output->writeln(
            "<info>Found ".count($backups)." backup(s).</>");

        if (!$total) {
            $output->writeln("<comment>If you're expecting backups to be present, "
                . "check the path (".$path.").</>");
        }

        if ($total) {
            $output->writeln("<info>Connecting and logging in to FTP "
                . "server.</>");

            $connected = true;

            try {
                @$this->client
                    ->connect(
                        $this->config->get('host'),
                        false,
                        $this->config->get('port')
                    )
                    ->login(
                        $this->config->get('username'),
                        $this->config->get('password')
                    );
            }
            catch (FtpException $e) {
                $output->writeln("<error>ERROR: ".$e->getMessage()."</>");
                $connected = false;
            }

            if ($connected !== false) {

                $output->writeln("<info>Transferring ".$total." backup(s)</>");

                $this->client->pasv($this->config->get('passive'));

                $complete = $this->pushBackups($backups);

                if ($complete == $total) {
                    $output->writeln("<info>Successfully transfered all "
                        . "backups</>");
                } else {
                    $output->writeln("<error>Failed to transfer " .
                        ($total - $complete) . "backup(s).</>");
                }
            }
        }
    }

    /**
     * Retrieves all the files for transfer based on the backup path defined
     * in the environment file.
     * 
     * @return array An array of files that match the src_path string.
     */
    private function getBackups($backupPath)
    {
        $backups = array_values(glob($backupPath));

        $keys = array();

        foreach ($backups as $backup) {
            $keys[] = pathinfo($backup)['basename'];
        }

        return array_combine($keys, array_values($backups));
    }

    /**
     * Pushes the backups found to the FTP server.
     * 
     * @param array $backups An array of backup file paths.
     * @return int The number of successful transfers.
     */
    private function pushBackups($backups)
    {
        $successful = 0;

        foreach ($backups as $file => $path) {
            try {
                $this->client->put($file, $path, FTP_ASCII);
                $successful+= 1;
                unlink($path);
            }
            catch (FtpException $e) {
                // Do nothing, we don't really care much.
            }
        }

        return $successful;
    }
}
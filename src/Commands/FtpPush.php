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
     * Initialised indicator
     * 
     * @var bool
     */
    private $init = false;

    /**
     * Initialises command with appropriate dependencies.
     * 
     * @param Config $config
     * @param FtpClient $client
     */
    public function init(Config $config, FtpClient $client)
    {
        $this->config = $config;
        $this->client = $client;
        $this->init = true;

        return $this;
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
        if (!$this->init) {
            throw new \RuntimeException("Command executed without being "
                . "initialised");
        }

        
    }
}
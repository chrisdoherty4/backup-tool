<?php

/*
 * Copyright (C) 2017 Chris Doherty
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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Backup\CPanel\CPanelInterface;

/**
 * @class CPanelBackup
 * Backs up a website via the CPanel full website backup feature. All CPanel
 * configuration is achieved through the Dotenv file located at a user defined
 * location.
 *
 * The command expects a single argument, the Dotenv directory path.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CPanelBackup extends AbstractCommand
{
    /**
     * @var CPanelInterface
     */
    private $cpanel = null;

    /**
     * Constructor.
     * @param CPanel $cpanel The CPanel interface to a CPanel website.
     */
    public function __construct(CPanelInterface $cpanel)
    {
        parent::__construct();

        $this->cpanel = $cpanel;
    }

    /**
     * Configures the command object.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('backup:cpanel')
            ->setTitle('CPanel Backup Command')
            ->setDescription(
                'A complete backup via cPanel that stashes the'
                . ' backup in the home directory.'
            );
    }

    /**
     * Logs in to cPanel and requests a backup be created to the home directory.
     *
     * @param  InputInterface  $input  Console input interface.
     * @param  OutputInterface $output Console output interface.
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getHeader());

        $output->writeln("<info>Logging in to CPanel interface.</>");

        $response = $this->cpanel->login();

        if ($this->cpanel->isResponseOk($response)) {
            $output->writeln("<info>Successfully logged in.</>");
            $output->writeln("<info>Requesting backup to home directory.</>");

            $response = $this->cpanel->requestFullWebsiteBackup();

            if ($this->cpanel->isResponseOk($response)) {
                $output->writeln(
                    "<info>Successfully requested backup. Backups"
                    . " take time to complete so may not appear instantly.</>"
                );
            } else {
                $output->writeln(
                    "<error>There was an error requesting a "
                    . "backup.</>"
                );
            }
        } else {
            $output->writeln(
                "<error>Failed to log in to CPanel. Check the "
                . "supplied credentials in the .env.cpanelbackup file.</>"
            );
        }
    }
}

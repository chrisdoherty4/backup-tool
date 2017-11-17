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

/**
 * @class Cleanup
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class Clean extends AbstractCommand
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Configures the command object.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('clean')
            ->setTitle('Clean Backups Command')
            ->setDescription(
                'Cleans backups found in some location. Cleaning means removing'
                .'old backups as per some specification.'
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
    }
}

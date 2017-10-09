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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;

/**
 * Description of CPanelBackupCommand
 *
 * @author chrisdoherty
 */
class CPanelBackupCommand extends Command
{
    public function configure() 
    {
        $this->setName('backup:cpanel')
                ->setDescription('A complete backup via cPanel')
                ->setArgument('backup-name', InputArgument::);
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        
    }
}

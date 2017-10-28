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

use \Cilex\Provider\Console\Command;
use \Symfony\Component\Console\Output\OutputInterface;

/**
 * @class AbstractCommand
 * A command abstraction handling common functionality across commands.
 */
abstract class AbstractCommand extends Command
{
    /**
     * The title fo the command.
     *
     * @var string
     */
    private $title = null;

    /**
     * Sets the command title.
     * 
     * @param strnig $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retrieves the command title.
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Retrieves a formatted command header.
     * 
     * @return string
     */
    public function getHeader()
    {
        $header = sprintf("%s\n%s\n%s\n%s\n",
            "==================================================",
            $this->getTitle(),
            (new \DateTime())->format('Y-m-d T H:i:sP'),
            "==================================================");

        return $header;
    }

    /**
     * Writes the command header to the console.
     * 
     * @param OutputInterface $output
     */
    public function writeHeader(OutputInterface $output)
    {
        $output->writeln($this->getCommandHeader());
    }
}


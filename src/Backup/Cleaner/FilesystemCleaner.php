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

namespace Backup\Cleanup;

use DateTime;
use InvalidArgumentException;
use League\Flysystem\Filesystem;
use Backup\Cleaner\FilesystemCleanerInterface;

/**
 * @class Cleanup
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FilesystemCleaner extends Filesystem implements FilesystemCleanerInterface
{
    /**
     * The uper limit of backups to keep.
     * @var int
     */
    private $keep = 1;

    /**
     * The date after which backups should be kept.
     * @var DateTime
     */
    private $keepAfter = null;

    /**
     * The regex used to identify backups.
     * @var string
     */
    private $regex = '/(.*)/';

    /**
     * Sets the regex for identifying backups to be considered for cleaning.
     *
     * @param string $regex
     * @return void
     */
    public function setFileRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Specifies the number of backups to keep starting with the latest
     * backup and working backwards.
     *
     * @param int $count The number of backpups to keep.
     * @return void
     */
    public function keep($count)
    {
        if (!is_int($count)) {
            throw new InvalidArgumentException('$count must be an int');
        }

        $this->keep = $keep;
    }

    /**
     * Specifies a date from which to keep backups.
     *
     * @param DateTime $date The date from which we should keep backups.
     * @return void
     */
    public function keepAfter(DateTime $date)
    {
        $current = new DateTime();

        if ($date > $current) {
            throw InvalidArgumentException(
                '$date cannot be later than current date-time'
            );
        }

        $this->keepAfter = $date;
    }

    /**
     * Performs the clean of backup files.
     *
     * @return int The number of items cleaned.
     */
    public function clean()
    {
        // Get files
        // Iterate over files and test if we should keep it.
        // If not, delete.
        // Increment files deleted counter.
        // Return files deleted.
    }

    private function shouldKeep($file)
    {

    }
}

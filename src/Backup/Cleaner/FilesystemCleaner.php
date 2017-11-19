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

namespace Backup\Cleaner;

use Backup\Cleaner\Exception\MisconfiguredException;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use League\Flysystem\Filesystem;

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
    private $keep = 0;

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
     * The numebr of backups kept.
     * @var int
     */
    private $kept = 0;

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
     * @return $this
     */
    public function keep($count)
    {
        if (!is_int($count)) {
            throw new InvalidArgumentException('$count must be an int');
        }

        $this->keep = $count;

        return $this;
    }

    /**
     * Specifies a date from which to keep backups.
     *
     * @param DateTime $date The date from which we should keep backups.
     * @return $this
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

        return $this;
    }

    /**
     * Performs the clean of files by checking if 1) the keep counter has
     * been reached and 2) the file is within the keep after datetime.
     *
     * @return int The number of items cleaned.
     */
    public function clean()
    {
        if ($this->keep == 0 && !$this->keepAfter) {
            throw new MisconfiguredException("Total number of items to keep, "
            ."or keepa fter date must be set");
        }

        $files = $this->listContents();

        usort($files, function ($a, $b) {
            return $a['timestamp'] > $b['timestamp'] ? -1 : 1;
        });

        $cleaned = 0;

        foreach ($files as $file) {
            if (preg_match($this->regex, $file['basename'])) {
                if (!$this->shouldKeep($file['path'])) {
                    $this->delete($file['path']);
                    $cleaned+= 1;
                }
            }
        }

        return $cleaned;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeep()
    {
        return $this->keep;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeepAfter()
    {
        return $this->keepAfter;
    }

    /**
     * {@inheritDoc}
     */
    public function getFileRegex()
    {
        return $this->regex;
    }

    private function shouldKeep($path)
    {
        return $this->isLaterThanKeepAfter($path)
            && $this->isKeepCountReached();
    }

    private function isLaterThanKeepAfter($path)
    {
        $result = true;

        if ($this->keepAfter) {
            if (!is_string($path)) {
                throw new InvalidargumentException('$path should be a string');
            }

            $datetime = new DateTime('@'.$this->getTimestamp($path));

            $result = $datetime > $this->keepAfter;
        }

        return $result;
    }

    private function isKeepCountReached()
    {
        return $this->keep < $this->kept;
    }

    private function incrementKeptBackups()
    {
        $this->backupsKept+= 1;
    }
}

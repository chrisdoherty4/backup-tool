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


use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use ArrayIterator;
use League\Flysystem\FilesystemInterface;
use Backup\Cleaner\FileMatcher\FileMatchingInterface;

/**
 * @class Cleanup
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FilesystemCleaner implements FilesystemCleanerInterface
{
    /**
     * The file system we're cleaning.
     *
     * @var FilesystemInterface
     */
    private $filesystem = null;

    /**
     * The uper limit of backups to keep.
     * @var int
     */
    private $keepCount = null;

    /**
     * The numebr of backups kept.
     * @var int
     */
    private $kept = 0;

    public function __construct(
        FilesystemInterface $filesystem,
        FileMatchingInterface $matcher,
        $keepCount
    ) {
        $this->filesystem = $filesystem;
        $this->matcher = $matcher;
        $this->setKeepCount($keepCount);
    }

    /**
     * Performs the clean of files by checking if 1) the keep counter has
     * been reached and 2) the file is within the keep after datetime.
     *
     * @return int The number of items cleaned.
     */
    public function clean()
    {
        $iterator = new ArrayIterator($this->filesystem->listContents());

        $iterator->uasort(function ($a, $b) {
            return $a['timestamp'] > $b['timestamp'] ? -1 : 1;
        });

        $cleaned = 0;

        if ($iterator->count() > 0) {
            while (!$this->isKeepCountReached() && $iterator->valid()) {
                $file = $iterator->current();

                if ($this->matcher->matches($file['path'])) {
                    $this->filesystem->delete($file['path']);
                    $cleaned++;
                }

                $iterator->next();
            }
        }

        return $cleaned;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeepCount()
    {
        return $this->keep;
    }

    /**
     * Retrieve the total number of items kept afte a clean.
     *
     * @return int
     */
    public function getKept()
    {
        return $this->kept;
    }

    /**
     * Set the keep count.
     *
     * @param int $count
     */
    private function setKeepCount($count)
    {
        if (!is_int($count)) {
            throw new InvalidArgumentException('$count must be an intefer');
        }

        $this->keepCount = $count;
    }

    private function isKeepCountReached()
    {
        return $this->keepCount && ($this->keepCount < $this->kept);
    }
}

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

namespace Backup\Cleaner\FileMatcher\Decorator;

use DateTime;
use League\Flysystem\FilesystemInterface;
use Backup\Cleaner\FileMatcher\FileMatchingInterface;

/**
 * Matches file names against some regex string.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FileTimestampAfterDecorator extends FileMatchingDecorator
{
    /**
     * The date time to check a file is after.
     *
     * @var DateTime
     */
    private $after = null;

    /**
     * The file system the file resides on.
     *
     * @var FilesystemInterface
     */
    private $filesystem = null;

    /**
     * @param FileMatchingInterface $matcher The file matcher to use next in
     *  the sequence.
     * @param DateTime $after The date-time to ensure files are created after.
     * @param FilesystemInterface $filesystem The filesystem we're interfacing
     *  with.
     */
    public function __construct(
        FileMatchingInterface $matcher,
        DateTime $after,
        FilesystemInterface $filesystem
    ) {
        parent::__construct($matcher);

        $this->after = $after;
        $this->filesystem = $filesystem;
    }

    /**
     * Matches against the file path.
     *
     * {@inheritDoc}
     */
    public function matches($path)
    {
        $fileDatetime = (new DateTime())->setTimestamp(
            $this->filesystem->getTimestamp($path)
        );

        return parent::matches($path) && ($this->after < $fileDatetime);
    }
}

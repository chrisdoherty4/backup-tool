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
     * @param MatcherInterface $matcher The next matcher to call.
     * @param string $regex The regex to match the file name against. Should
     *  take into consideration the possibility of a full path name.
     * @param FilesystemInterface $filesystem The file system where the
     *  file will reside.
     */
    public function __construct(
        MatcherInterface $matcher,
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

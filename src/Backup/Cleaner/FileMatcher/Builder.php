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

namespace Backup\Cleaner\FileMatcher;

use FileNameMatcher;
use Backup\Cleaner\FileMatcher\Decorator\FileTimestampAfterDecorator;
use League\Flysystem\FilesystemInterface;

/**
 * A builder for creating a file matching decorator instance.
 */
class Builder
{
    /**
     * The file matching object. This is a decorator instance.
     *
     * @var FileMatchingInterface
     */
    private $matcher = null;

    /**
     * @param string $regex Regular expression for file name matching.
     */
    public function __construct($regex)
    {
        $this->matcher = new FileNameMatcher($regex);
    }

    /**
     * Add the file timestamp matcher component to the matcher construct. This
     * will ensure the file was created after the specified date and time.
     *
     * @param DateTime $keepAfter The datetime to match against.
     */
    public function setFileTimestampAfter(
        DateTime $keepAfter,
        FilesystemInterface $filesystem
    ) {
        $this->matcher = new FileTimestampAfterDecorator(
            $this->matcher,
            $keepAfter,
            $filessytem
        );
    }

    /**
     * Retrieves the file matching object.
     *
     * @return FileMatcherInterface The file matching instance.
     */
    public function getFileMatcher()
    {
        return $this->matcher;
    }
}

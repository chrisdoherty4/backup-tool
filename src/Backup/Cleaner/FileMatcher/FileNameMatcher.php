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

/**
 * Matches file names against some regex string.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FileNameMatcher implements FileMatchingInterface
{
    /**
     * The regex to match file paths against.
     *
     * @var string
     */
    private $regex = '/(.*)/';

    /**
     * @param string $regex The regex to match the file name against. Should
     *  take into consideration the possibility of a full path name.
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Matches against the file path.
     *
     * {@inheritDoc}
     */
    final public function matches($path)
    {
        return preg_match($this->regex, $path) === 1;
    }
}

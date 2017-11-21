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

use Backup\Cleaner\FileMatcher\FileMatchingInterface;

/**
 * The root file matcher that doesn't posess any specific matching logic. This
 * class merely kicks off the matching sequence.
 *
 * @author Chris Dohety <chris.doherty4@gmail.com>
 */
abstract class FileMatchingDecorator implements FileMatchingInterface
{
    /**
     * The matcher to call first.
     *
     * @var MatcherInterface
     */
    protected $matcher = null;

    /**
     * Sets the decorator up with the next matcher.
     *
     * @param MatcherInterface $matcher The next matcher.
     */
    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * Merely calls through to the first matcher.
     *
     * {@inheritDoc}
     */
    public function matches($path)
    {
        return $this->matcher->matches($path);
    }
}

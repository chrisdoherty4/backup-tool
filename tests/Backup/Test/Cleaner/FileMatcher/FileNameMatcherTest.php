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

namespace Backup\Test\Cleaner\FileMatcher;

use Backup\Cleaner\FileMatcher\FileNameMatcher;
use Backup\Cleaner\FileMatcher\FileMatcherInterface;

/**
 * FileNameMatcherTest
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FileNameMatcherTest
{
    public function testConstruction()
    {
        $fileNameMatcher = new FileNameMatcher('/(.*)/');

        $this->assertInstanceOf(FileNameMatcher::class, $fileNameMatcher);

        $this->assertInstanceOf(
            FileNameMatcherInterface::class,
            $fileNameMatcher
        );
    }

    public function testMatches()
    {
        $fileNameMatcher = new FileNameMatcher('/(.*)/');

        $this->assertTrue($fileNameMatcher->matches(''));

        $fileNameMatcher = new FileNameMatcher('/(test)/');

        $this->assertTrue($fileNameMatcher->matches('test'));

        $this->assertFalse($fileNameMatcher->matches(''));

        $this->assertFalse($fileNameMatcher->matches('random'));
    }
}

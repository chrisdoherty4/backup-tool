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

use PHPUnit\Framework\TestCase;
use Backup\Cleaner\FileMatcher\FileNameMatcher;
use Backup\Cleaner\FileMatcher\FileMatchingInterface;

/**
 * FileNameMatcherTest
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FileNameMatcherTest extends TestCase
{
    public function testConstruction()
    {
        $fileNameMatcher = new FileNameMatcher('/(.*)/');

        $this->assertInstanceOf(FileNameMatcher::class, $fileNameMatcher);

        $this->assertTrue($fileNameMatcher instanceof FileMatchingInterface);
    }

    public function testMatches()
    {
        $fileNameMatcher = new FileNameMatcher('/(.*)/');

        $random = 'abcdefghijklmnopqrstuvwxyz0123456789!£$%^&*()@:}{}<>';

        for ($i = 0; $i < 100; ++$i) {
            $this->assertTrue($fileNameMatcher->matches(
                substr(str_shuffle($random), 0, rand(5, strlen($random)))
            ));
        }

        $fileNameMatcher = new FileNameMatcher('/(test)/');

        $this->assertTrue($fileNameMatcher->matches('test'));

        for ($i = 0; $i < 100; ++$i) {
            $this->assertFalse($fileNameMatcher->matches(
                substr(str_shuffle($random), 0, rand(5, strlen($random)))
            ));
        }
    }
}

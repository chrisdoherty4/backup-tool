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

namespace Backup\Test\Cleaner\FileMatcher\Decorator;

use DateTime;
use DateInterval;
use PHPUnit\Framework\TestCase;
use Backup\Cleaner\FileMatcher\FileNameMatcher;
use Backup\Cleaner\FileMatcher\Decorator\FileMatchingDecorator;
use Backup\Cleaner\FileMatcher\Decorator\FileTimestampAfterDecorator;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * FileTimestampAfterDecoratorTest
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class FileTimestampAfterDecoratorTest extends TestCase
{
    public function testConstruction()
    {
        $matcher = new FileTimestampAfterDecorator(
            new FileNameMatcher('/(.*)/'),
            new DateTime(),
            new Filesystem(new MemoryAdapter())
        );

        $this->assertInstanceOf(FileTimestampAfterDecorator::class, $matcher);
    }

    public function testMatchTimestamps()
    {
        $filesystem = new Filesystem(new MemoryAdapter());
        $datetime = new DateTime();

        $matcher = new FileTimestampAfterDecorator(
            new FileNameMatcher('/(.*)/'),
            $datetime->sub(new DateInterval('P1D')),
            $filesystem
        );

        $filesystem->write(
            'test1',
            'test1',
            ['timestamp' => $datetime->format('U')]
        );

        $filesystem->write(
            'test2',
            'test2',
            [
                'timestamp' => $datetime->sub(
                    new DateInterval('P2D')
                )->format('U')
            ]
        );

        $this->assertTrue($matcher->matches('test1'));
        $this->assertFalse($matcher->matches('test2'));
    }
}

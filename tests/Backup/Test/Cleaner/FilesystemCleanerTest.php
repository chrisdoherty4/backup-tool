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

namespace Backup\Test\Cleaner;

use DateTime;
use PHPUnit\Framework\TestCase;
use Backup\Cleaner\FilesystemCleaner;
use Backup\Cleaner\FileMatcher\FileNameMatcher;
use Backup\Cleaner\FilesystemCleanerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

class FilesystemCleanerTest extends TestCase
{
    public function testConstruction()
    {
        $filesystem = new Filesystem(new MemoryAdapter());

        $cleaner = new FilesystemCleaner(
            $filesystem,
            new FileNameMatcher('#(.*)#'),
            1
        );

        $this->assertInstanceOf(FilesystemCleaner::class, $cleaner);
        $this->assertInstanceOf(FilesystemCleanerInterface::class, $cleaner);

        $this->assertEquals($cleaner->getKeepCount(), 1);
    }

    public function testClean()
    {
        $filesystem = new Filesystem(new MemoryAdapter());

        $cleaner = new FilesystemCleaner(
            $filesystem,
            new FileNameMatcher('#^matching[0-9]{1,2}$#'),
            10
        );

        for ($i = 0; $i < 10; $i++) {
            $filesystem->write('matching'.$i, 'matching');
        }

        for ($i = 0; $i < 5; $i++) {
            $filesystem->write('nonmatching'.$i, 'nonmatching');
        }

        $cleaned = $cleaner->clean();

        $this->assertEquals(10, $cleaned, 'Cleaned count wrong');

        $this->assertEquals(5, $cleaner->getKept(), 'Kept count wrong');
    }
}

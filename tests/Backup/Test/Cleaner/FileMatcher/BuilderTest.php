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

use DateTime;
use PHPUnit\Framework\TestCase;
use Backup\Cleaner\FileMatcher\Builder as FileMatchingBuilder;
use Backup\Cleaner\FileMatcher\FileNameMatcher;
use Backup\Cleaner\FileMatcher\FileMatchingInterface;
use Backup\Cleaner\FileMatcher\Decorator\FileTimestampAfterDecorator;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * FileNameMatcherTest
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class BuilderTest extends TestCase
{
    public function testBuilder()
    {
        $builder = new FileMatchingBuilder('/(.*)/');

        $this->assertInstanceOf(
            FileNameMatcher::class,
            $builder->getFileMatcher()
        );

        $this->assertTrue(
            $builder->getFileMatcher() instanceof FileMatchingInterface
        );

        $builder->setFileTimestampAfter(
            new DateTime(),
            new Filesystem(new MemoryAdapter())
        );

        $this->assertInstanceOf(
            FileTimestampAfterDecorator::class,
            $builder->getFileMatcher()
        );
    }
}

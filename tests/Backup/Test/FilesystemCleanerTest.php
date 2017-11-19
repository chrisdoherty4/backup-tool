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

namespace Backup\Test;

use DateTime;
use PHPUnit\Framework\TestCase;
use Backup\Cleaner\FilesystemCleaner;
use League\Flysystem\Adapter\Local as LocalAdapter;

class FilesystemCleanerTest extends TestCase
{
    private static $tmpDir = __DIR__.'/tmp';

    /**
     * Create a directory for files so we can test things.
     */
    public static function setUpBeforeClass()
    {
        if (!is_dir(self::$tmpDir)) {
            mkdir(self::$tmpDir);
        }
    }

    /**
     * Clean up the directory used for testing things.
     */
    public static function tearDownAfterClass()
    {
        if (is_dir(self::$tmpDir)) {
            foreach (array_diff(scandir(self::$tmpDir), ['.','..']) as $file) {
                unlink(self::$tmpDir.'/'.$file);
            }

            rmdir(self::$tmpDir);
        }
    }

    public function testSetup()
    {
        $filecleaner = new FilesystemCleaner(new LocalAdapter(self::$tmpDir));

        for ($i = 1; $i < 10; ++$i) {
            $filecleaner->keep($i);
            $this->assertEquals($i, $filecleaner->getKeep());
        }

        $datetime = (new DateTime())->modify('-1 day');
        $this->assertEquals(
            $filecleaner->keepAfter($datetime)->getKeepAfter(),
            $datetime
        );

        $datetime->modify('-1 month');
        $this->assertEquals(
            $filecleaner->keepAfter($datetime)->getKeepAfter(),
            $datetime
        );
    }

    public function testKeepCleanCount()
    {
        $filecleaner = new FilesystemCleaner(new LocalAdapter(self::$tmpDir));

        $datetime = new DateTime();

        for ($i = 1; $i <= 4; ++$i) {
            $this->createFile('test'.$i, $datetime->modify('-'.$i.' day'));
        }

        $filecleaner->keep(2);

        $cleaned = $filecleaner->clean();

        $this->assertEquals($cleaned, 2);

        $files = $filecleaner->listContents();

        $basenames = array_column($files, 'basename');

        $this->assertCount(2, $basenames);
        $this->assertContains('test1', $basenames);
        $this->assertContains('test2', $basenames);
    }

    private function createFile($name, DateTime $created = null)
    {
        if (!$created) {
            $created =  new DateTime();
        }

        $file = self::$tmpDir.'/'.$name;

        file_put_contents($file, '');
        touch($file, $created->getTimestamp());
    }
}

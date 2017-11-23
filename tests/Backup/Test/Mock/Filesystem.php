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

use League\Flysystem\Filesystem as BaseFilesystem;
use League\Fysystem\Adapter\Local as LocalAdapter;

/**
 * Filesystem
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class Filesystem extends BaseFilesystem
{
    public function __construct()
    {
        mkdir(__DIR__.'/tmp');
        parent::__construct(new LocalAdapter(__DIR__.'/tmp'));
    }

    public function __destruct()
    {
        foreach(array_diff(scandir(__DIR__.'/tmp'), ['.', '..']) as $f) {
            unlink(__DIR__.'/'.$f);
        }
    }

    public function populate($num = 5, $timestamp = 'now')
    {
        for ($i = 0; $i < $num; ++$i) {
            $this->write('test'.$i, '');
        }
    }
}

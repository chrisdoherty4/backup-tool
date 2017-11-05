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

use PHPUnit\Framework\TestCase;
use Backup\App as BackupApplication;
use Backup\Commands\CPanelBackup as CPanelBackupCommand;
use Backup\Commands\Relocate as RelocateCommand;

class ApplicationTest extends TestCase
{
    public function testBootstrap()
    {
        $app = require base_path('/bootstrap/app.php');

        $this->assertInstanceOf(
            CPanelBackupCommand::class,
            $app['console']->find('backup:cpanel')
        );

        $this->assertInstanceOf(
            RelocateCommand::class,
            $app['console']->find('relocate:ftp')
        );
    }

    public function testConstruction()
    {
        $app = new BackupApplication("Testing Instance");

        $this->assertInstanceOf(BackupApplication::class, $app);
    }
}

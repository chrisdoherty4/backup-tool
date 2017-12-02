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

namespace Backup\Providers;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Backup\Commands\CPanelBackup as CPanelBackupCommand;
use Backup\Commands\Relocate as RelocateCommand;
use Backup\Commands\Clean as CleanCommand;
use Backup\Cleaner\FilesystemCleaner;

/**
 * @class CommandsServiceProvider
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CommandsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        $c['Backup\Commands\CPanelBackup'] = function (Container $c) {
            return new CPanelBackupCommand($c['cpanel']);
        };

        $c['Backup\Commands\Relocate'] = function (Container $c) {
            return new RelocateCommand(
                $c['filesystem.mount_manager'],
                $c['config.relocate']['ftp']
            );
        };

        // $c['Backup\Commands\Clean'] = function (Container $c) {
        //     return new CleanCommand(
        //         new FilesystemCleaner(
        //             new \League\Flysystem\Adapter\Ftp(
        //                 [
        //                 'host' => $c['config.relocate']['ftp']['host'],
        //                 'username' => $c['config.relocate']['ftp']['username'],
        //                 'password' => $c['config.relocate']['ftp']['password'],
        //                 'port' => $c['config.relocate']['ftp']['port'],
        //                 'passive' => $c['config.relocate']['ftp']['passive']
        //                 ]
        //             )
        //         )
        //     );
        // };
    }
}

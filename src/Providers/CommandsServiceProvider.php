<?php

/* 
 * Copyright (C) 2017 chrisdoherty
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

use \Pimple\ServiceProviderInterface;
use \Pimple\Container;
use \Backup\Commands\CPanelBackupCommand;
use \Backup\Commands\FtpPushCommand;

/**
 * @class CommandsServiceProvider
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CommandsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c) 
    {
        $c['\Backup\Commands\CPanelBackupCommand'] = function (Container $c) {
            $command = new CPanelBackupCommand();
            
            $command->setCPanelConfig($c['cpanel_config']);
            $command->setHttpClient($c['http_client']);
            
            return $command;
        };

        $c['\Backup\Commands\FtpPushCommand'] = function (Container $c) {
            return (new FtpPushCommand())
                ->init($c['ftp_push_config'], $c['ftp_client']);
        };
    }
}
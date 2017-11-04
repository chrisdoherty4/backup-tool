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

use \Pimple\ServiceProviderInterface;
use \Pimple\Container;
use \PHLAK\Config\Config;

/**
 * @class ConfigServiceProvider
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        // Read all configuration files.
        $configPath = config_path();
        $files = array_diff(scandir($configPath), ['.', '..']);
        
        foreach ($files as $file) {
            $fileName = substr($file, 0, strrpos($file, '.'));
            
            $c['config.'.$fileName] = function (Container $c) use (
                $file,
                $configPath
            ) {
                return new Config($configPath."/".$file);
            };
        }
    }
}

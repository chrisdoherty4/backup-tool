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

namespace Backup\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface
use Dotenv\Dotenv;

/**
 * Description of DotEnvServiceProvider
 *
 * @author chrisdoherty
 */
class DotEnvServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers the Dotenv object with the pimple container.
     * 
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['env'] = function ($pimple) {
            return new Dotenv($pimple['env_path']);
        };
    }
}

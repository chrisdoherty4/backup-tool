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

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Backup\Providers\Factory\HttpFactory;

/**
 * @class HttpServiceProvider
 * @author Chris Dohety <chris.doherty4@gmail.com>
 */
class HttpServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        $c['http.factory'] = function (Container $c) {
            return new HttpFactory($c);
        };
    }
}

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

require_once __DIR__ . "../vendor/autoload.php";

/**
 * Define the dependency manager (Pimple).
 */
$container = new Pimple\Psr11\Container(new Pimple\Container());

/**
 * Register all the defined service providers from the 
 * src/config/service_providers.php configuration file.
 */
$serviceProviders = require_once __DIR__ . "config/service_providers.php";

foreach ($serviceProviders as $serviceProvider) {
    $container->register($serviceProvider);
}

return $container;
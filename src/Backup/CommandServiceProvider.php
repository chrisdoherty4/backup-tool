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

namespace Backup;

use \Pimple\ServiceProviderInterface;

/**
 * Service provider to register all command objects with our application
 * container.
 *
 * @author chrisdoherty
 */
class CommandServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers all commands with the application container.
     */
    public function register(Container $container)
    {
        // Register commands here.
    }
}

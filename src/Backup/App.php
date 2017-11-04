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
 * along with tihs program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Backup;

use \Cilex\Application as CommandConsole;

/**
 * App is the root of the backup tool application. It extends the 
 * \Cilex\Application class giving it access to the \Pimple\Container
 * container. 
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class App extends CommandConsole
{
    /**
     * Registers an array of service providers.
     * 
     * @param  array $providers
     * @return $this
     */
    public function registerMultiple(array $providers) 
    {
        foreach ($providers as $provider) {
            $this->register($provider);
        }
        
        return $this;
    }
}

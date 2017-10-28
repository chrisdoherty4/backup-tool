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

if (!function_exists('env')) {
    
    /**
     * Retrieves an environment variable with an optional default should the 
     * variable not exist. 
     * 
     * @param string $key The key of the environment variable.
     * @param mixed $default The default value (null if not set).
     * @return mixed
     */
    function env($key, $default = null) 
    {
        $value = getenv($key);
        
        if ($value === false) {
            $value = $default;
        }
        
        switch (strtolower($value)) {
            case "true":
                $value = true;
                break;
            case "false":
                $value = false;
                break;
            case "null": 
                $value = null;
                break;
        }
        
        return $value;
    }
    
}

if (!function_exists('base_path')) {
    
    /**
     * Creates an absolute path to the root application directory.
     * 
     * @param string $path An optional extension to the base path. 
     * @return string The path.
     * 
     * @throws \RuntimeException Thrown if the path is invalid.
     */
    function base_path($path = "") 
    {   
        $path = realpath(__DIR__ . '/../../..' . rtrim($path, '/'));
        
        if ($path === false) {
            throw new \RuntimeException("Path does not exist.");
        }
        
        return $path;
    }
}

if (!function_exists('config_path')) {
    
    /**
     * Creates a path to the config directory with an optional extension.
     * 
     * @param string $path
     * @return string The path.
     * 
     * @throws \RunetimeException Thrown if the path is invalid.
     */
    function config_path($path = "") 
    {        
        return base_path("/config" . $path);
    }
}
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

return [
    /**
     * The host of the FTP server to push to. This can be a domain name or 
     * URI.
     */
    'host' => env('FTP_TARGET_HOST'),
    
    /**
     * The port of the FTP server.
     */
    'port' => env('FTP_TARGET_PORT'),
    
    /**
     * The username to log in with.
     */
    'username' => env('FTP_TARGET_USER'),
    
    /**
     * The password for the username.
     */
    'password' => env('FTP_TARGET_PASS'),

    /**
     * Set passive transfer mode on or off.
     */
    'passive' => true
];
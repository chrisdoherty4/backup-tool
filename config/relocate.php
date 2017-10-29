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

return [    
    'ftp' => [
        /**
         * The host of the FTP server to push to. This can be a domain name or
         * URI.
         */
        'host' => env('RELOCATE_FTP_HOST'),

        /**
         * The username to log in with.
         */
        'username' => env('RELOCATE_FTP_USER'),

        /**
         * The password for the username.
         */
        'password' => env('RELOCATE_FTP_PASS'),

        /**
         * The port of the FTP server.
         */
        'port' => env('RELOCATE_FTP_PORT'),

        /**
         * Set passive transfer mode on or off.
         */
        'passive' => true
    ]
];
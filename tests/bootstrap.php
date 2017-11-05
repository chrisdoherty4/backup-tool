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

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * ---------------------------------------------------------------------------
 * Load Environment
 * ---------------------------------------------------------------------------
 *
 * Load environment variables. These are used in the configuration files and
 * accessed via the <code>env()</code> function.
 */
try {
    (new \Dotenv\Dotenv(__DIR__))->load();
} catch (\Dotenv\Exception\InvalidPathException $ex) {
    echo "Could not load environment\n";
    exit(1);
}

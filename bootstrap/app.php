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

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * ---------------------------------------------------------------------------
 * Load Environment
 * ---------------------------------------------------------------------------
 * 
 * Load the environment variables.
 */
try {
    (new \Dotenv\Dotenv(__DIR__ . "/../"))->load();
} catch (\Dotenv\Exception\InvalidPathException $ex) {
    // Environment not loaded.
}

/**
 * ---------------------------------------------------------------------------
 * Create Console Application
 * ---------------------------------------------------------------------------
 * 
 * Define the dependency manager (Pimple).
 */
$app = new \Backup\App("Backup Tool");

/**
 * ---------------------------------------------------------------------------
 * Register Service Providers
 * ---------------------------------------------------------------------------
 * 
 * Register our service providers.
 */
$app->registerMultiple(require config_path('/providers.php'));

/**
 * ---------------------------------------------------------------------------
 * Boot App
 * ---------------------------------------------------------------------------
 * 
 * Now we want to boot the application so the service providers register each 
 * of their respective services.
 */
$app->boot();

/**
 * ---------------------------------------------------------------------------
 * Command Registration
 * ---------------------------------------------------------------------------
 * 
 * Lets register the commands we expet for the application. 
 */
$app->command($app['cpanel_backup_command']);
//$app->command($app['ftp_transfer_command']);
//$app->command($app['target_cleanup_command']);

return $app;
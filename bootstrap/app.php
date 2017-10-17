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
 * Load environment variables. These are used in the configuration files and 
 * accessed via the <code>env()</code> function.
 * 
 * @todo what shall we do if an exception is thrown?
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
 * Define the console application. This is essentially a Cilex app that feeds
 * off Symfony with some added magic.
 */
$app = new \Backup\App("Backup Tool");

/**
 * ---------------------------------------------------------------------------
 * Register Service Providers
 * ---------------------------------------------------------------------------
 * 
 * Register our service providers. The service providers are ordered such 
 * that dependencies are fulfilled.
 */
$app->registerMultiple(require base_path('/bootstrap/providers.php'));

/**
 * ---------------------------------------------------------------------------
 * Boot App
 * ---------------------------------------------------------------------------
 * 
 * Now we want to boot the application so the service providers register each 
 * of their respective services. This is important as we're adding commands
 * next that have dependencies.
 */
$app->boot();

/**
 * ---------------------------------------------------------------------------
 * Command Registration
 * ---------------------------------------------------------------------------
 *
 * Lets register the commands we expet for the application.
 */
$app->command($app['\Backup\Commands\CPanelBackup']);
$app->command($app['\Backup\Commands\FtpPush']);


return $app;
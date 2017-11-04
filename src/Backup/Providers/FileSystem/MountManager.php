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

namespace Backup\Providers\FileSystem;

use \Pimple\Container;
use \League\Flysystem\MountManager as LeagueMountManager;
use \League\Flysystem\Filesystem;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;
use \League\Flysystem\Adapter\Local as LocalAdapter;

/**
 * @class MountManager
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class MountManager extends LeagueMountManager
{
    /**
     * A reference to the pimpl container.
     * 
     * @var \Pimple\Container
     */
    private $container = null;

    /**
     * The required fields for an FTP adapter configuration.
     *
     * @var array
     */
    private $ftpConfigFields = [
        'host',
        'username',
        'password',
        'port',
        'passive'
    ];

    /**
     * Constructor.
     * 
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct();
        
        $this->container = $container;
    }

    /**
     * Retrieves the container used for dependency injection.
     *
     * @return \Pimple\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Retrieves an instance of an FTP Filsystem.
     *
     * @param  array $config The FTP configuration.
     * @return Filesystem
     */
    public function getFtpInstance($config)
    {
        return new Filesystem(
            new FtpAdapter(
                [
                'host' => $config['host'],
                'username' => $config['username'],
                'password' => $config['password'],
                'port' => $config['port'],
                'passive' => $config['passive']
                ]
            )
        );
    }

    /**
     * Retrieves a file system for the local system.
     * 
     * @param  string $path The root path. 
     * @return Filesystem
     */
    public function getLocalInstance($path)
    {
        return new Filesystem(new LocalAdapter($path));
    }

    /**
     * Validates the supplied configuration against the required fields.
     * 
     * @param  array $config The config to validate.
     * @return bool|array True if valid, else an array of missing fields.
     */
    public function isValidFtpConfig($config)
    {
        $diff = array_diff($config, $this->ftpConfigFields);

        $result = true;

        if (count($diff) > 0) {
            $result = $diff;
        }

        return $result;
    }
}
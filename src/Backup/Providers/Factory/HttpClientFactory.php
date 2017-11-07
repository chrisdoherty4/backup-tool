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

namespace Backup\Providers\Factory;

use Pimple\Pimple;
use GuzzleHttp\Client;

/**
 * @class HttpClientFactory
 * Factories HTTP client objects. 
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class HttpClientFactory
{
    /**
     * The dependency container.
     * 
     * @var Pimple\Pimple
     */
    private $container = null;

    /**
     * Constructor.
     * 
     * @param Pimple $container
     */
    public function __construct(Pimple $container)
    {
        $this->container = $container;
    }
    
    /**
     * Retrieves an instance of the HttpFactory.
     *
     * @param array $args Constructor arguments for the http client.
     * @return HttpClien
     */
    public function instance(array $args)
    {        
        return new HttpClient($args);
    }
}
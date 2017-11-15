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

use GuzzleHttp\Client;

/**
 * @class Httpactory
 * Factories HTTP objects.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class HttpFactory
{
    /**
     * Retrieves an instance of the an Http Client.
     *
     * @param array $args Constructor arguments for the http client.
     * @return GuzzleHttp\ClientInterface
     */
    public function getClientInstance(array $args)
    {
        return new Client($args);
    }
}

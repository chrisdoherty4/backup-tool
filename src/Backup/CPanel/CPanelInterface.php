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

namespace Backup\CPanel;

use GuzzleHttp\Psr7\Response;

/**
 * @interface Interface to a CPanel website.
 */
interface CPanelInterface
{
    /**
     * Request a login to the CPanel website.
     *
     * @return boolean
     */
    public function login();

    /**
     * Request a full website backup.
     *
     * @return boolean
     */
    public function requestFullWebsiteBackup();

    /**
     * Check if a response is considered Ok.
     *
     * @param Response $response The resposne to check.
     * @return boolean True if Ok, else false.
     */
    public function isResponseOk(Response $response);

    /**
     * Retrieves the HttpClient the object wraps around.#
     *
     * @return ClientInterface
     */
    public function getHttpClient();

    /**
     * Retrieves the username used to log into CPanel.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Retrieves the last response.
     *
     * @return Response The last response recived by the last http request.
     */
    public function getLastResponse();

    /**
     * Checks to see if we're logged in to cpanel.
     *
     * @return boolean True if logged in, else false.
     */
    public function isLoggedIn();
}

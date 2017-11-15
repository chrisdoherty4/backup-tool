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

use Backup\CPanel\CPanelInterface;
use Backup\CPanel\Exception\NotLoggedInException;
use Backup\CPanel\Exception\AlreadyLoggedInException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

/**
 * @class CPanel
 * Interfaces CPanel commands with the application. Provides methods for
 * logging in to and submitting backup requests.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CPanel implements CPanelInterface
{
    /**
     * @var GuzzleHttp\Client
     */
    private $client = null;

    /**
     * @var string
     */
    private $username = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * Whether the Http requests should output debug.
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * @var GuzzleHttp\Psr7\Response
     */
    private $lastResponse = null;

    /**
     * @var boolean
     */
    private $loggedIn = false;

    /**
     * @var PHLAK\Config\Config
     */
    private $config = null;

    /**
     * @var string
     */
    private $pathPrefix = '';

    /**
     * Constructor
     *
     * @param GuzzleHttp\ClientInterface $client The client to interface
     *  witht he cpanel website.
     * @param string $username The username to log in to cpanel.
     * @param string $password The password for the $username.
     */
    public function __construct(
        ClientInterface $client,
        $username,
        $password,
        $debug = false
    ) {
        $this->client = $client;
        $this->setCredentials($username, $password);
        $this->debug = $debug;
    }

    /**
     * Set the credentials for logging in to cPanel.
     *
     * @param string $username
     * @param string $password
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Retrieve the username for the cPanel interface.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Retrieves the Http Client used to communicate with cPanel.
     *
     * @return GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Retrieves the last response.
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Determines if a successful login has been performed.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    /**
     * Logs in to cPanel.
     *
     * @return boolean True if successful, else false.
     * @throws Backup\CPanel\Exception\NotLoggedInException
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            throw new AlreadyLoggedInException();
        }

        $result = $this->request(
            'POST',
            '/login',
            [
                'form_params' => [
                    'user' => $this->username,
                    'pass' => $this->password,
                    'goto_uri' => '/'
                ]
            ]
        );

        if ($result) {
            $this->pathPrefix = $this->extractLoginResponsePath($this->lastResponse);
            $this->setLoggedIn();
        }

        return $result;
    }

    /**
     * Makes a full website backup request.
     *
     * @return boolean True if successful, else false.
     * @throws Backup\CPanel\Exception\AlreadyLoggedInException
     */
    public function requestFullWebsiteBackup()
    {
        if (!$this->isLoggedIn()) {
            throw new NotLoggedInException();
        }

        return $this->request(
            'POST',
            $this->factoryPath('/backup/wizard-dofullbackup.html'),
            [
                'form_params' => [
                    'dest' => 'homedir',
                    'email_radio' => 0
                ]
            ]
        );
    }

    /**
     * Verify the response code is acceptable.
     *
     * @param GuzzleHttp\Psr7\Response $response
     * @return boolean
     */
    public function isResponseOk(Response $response)
    {
        return preg_match(
            '/^(2|3)[0-9]{2}$/',
            $response->getStatusCode()
        ) === 1;
    }

    /**
     * Makes an http request.
     *
     * @param string $method Http method.
     * @param string $path Url path segment.
     * @param array $args Http parameters as per GuzzleHttp
     */
    private function request(
        $method,
        $path,
        $args
    ) {
        $this->lastResponse = $this->client->request(
            $method,
            $this->factoryPath($path),
            array_merge($args, ['debug' => $this->debug])
        );

        return $this->isResponseOk($this->lastResponse);
    }

    /**
     * Extracts the path from a response to a login request.
     *
     * @param GuzzleHttp\Psr7\Response $response The response received
     *  from a login request.
     * @return string The path with no index.html on the end.
     */
    private function extractLoginResponsePath(Response $response)
    {
        $path = (new Uri(
            $response->getHeader('Location')[0]
        ))->getPath();

        return substr($path, 0, strrpos($path, "/"));
    }

    /**
     * Factories a path from the path prefix set on login and the path
     * argument.
     *
     * @param string $path The path to create a full path from.
     * @return string The full path.
     */
    private function factoryPath($path)
    {
        return sprintf("/%s/%s", $this->pathPrefix, ltrim($path, '/'));
    }

    /**
     * Sets the logged in status as true.
     *
     * @return void
     */
    private function setLoggedIn()
    {
        $this->loggedIn = true;
    }
}

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

namespace Backup\Test;

use PHPUnit\Framework\TestCase;
use Backup\CPanel\CPanel;
use Backup\CPanel\Exception\AlreadyLoggedInException;
use Backup\CPanel\Exception\NotLoggedInException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class CPanelTest extends TestCase
{
    public function testConstruction()
    {
        $client = new Client(['handler' => new MockHandler()]);

        $cpanel = new CPanel(
            $client,
            'user',
            'password'
        );

        $this->assertInstanceOf(CPanel::class, $cpanel);

        $this->assertEquals($cpanel->getUsername(), 'user');

        $this->assertEquals($cpanel->getHttpClient(), $client);
    }

    public function testSuccessfulLogin()
    {
        $handler = new MockHandler([
            new Response(
                200,
                [
                    'Location' => ''
                ]
            )
        ]);

        $client = new Client(['handler' => $handler]);

        $cpanel = new CPanel($client, 'user', 'password');

        $cpanel->login();

        $this->assertEquals($cpanel->getLastResponse()->getStatusCode(), 200);

        $this->assertTrue($cpanel->isLoggedIn());
    }

    public function testFailedLogin()
    {
        $handler = new MockHandler([
            new Response(
                404,
                [
                    'Location' => ''
                ]
            )
        ]);

        $client = new Client(['handler' => $handler]);

        $cpanel = new CPanel($client, 'user', 'password');

        $cpanel->login();

        $this->assertFalse($cpanel->isLoggedIn());
    }

    public function testAlreadyLoggedIn()
    {
        $handler = new MockHandler([
            new Response(
                200,
                [
                    'Location' => ''
                ]
            ),
            new Response (
                200,
                [
                    'Location' => ''
                ]
            )
        ]);

        $client = new Client(['handler' => $handler]);

        $cpanel = new CPanel($client, 'user', 'password');

        $cpanel->login();

        $this->expectException(AlreadyLoggedInException::class);

        $cpanel->login();
    }

    public function testFullWebsiteBackupNotLoggedIn()
    {
        $handler = new MockHandler([
            new Response(
                200,
                [
                    'Location' => ''
                ]
            )
        ]);

        $client = new Client(['handler' => $handler]);

        $cpanel = new CPanel($client, 'user', 'password');

        $this->expectException(NotLoggedInException::class);

        $cpanel->requestFullWebsiteBackup();
    }

    public function testFullWebsiteBackup()
    {
        $handler = new MockHandler([
            new Response(
                200,
                [
                    'Location' => ''
                ]
            ),
            new Response(
                200,
                [
                    'Location' => ''
                ]
            )
        ]);

        $client = new Client(['handler' => $handler]);

        $cpanel = new CPanel($client, 'user', 'password');

        $cpanel->login();

        $this->assertEquals($cpanel->getLastResponse()->getStatusCode(), 200);

        $cpanel->requestFullWebsiteBackup();

        $this->assertEquals($cpanel->getLastResponse()->getStatusCode(), 200);
    }
}

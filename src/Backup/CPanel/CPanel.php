<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\CPanel;

use Backup\Providers\Factory\HttpClientFactory;
use GuzzleHttp\Psr7\Response;
use PHLAK\Config\Config;

/**
 * @class CPanel
 * 
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class CPanel
{
    /**
     * @var GuzzleHttp\Client
     */
    private $client = null;

    /**
     * @var GuzzleHttp\Response
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
    
    public function __construct(HttpClientFactory $factory, Config $config)
    {
        $this->client = $factory->instance($config);

        $this->setConfig($config);
    }

    public function getHttpClient()
    {
        return $this->client;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function login()
    {
        $this->lastResponse = $this->client->request(
            'POST',
            '/login',
            [
            'form_params' => [
                'user' => $this->config->get('username'),
                'pass' => $this->config->get('password'),
                'goto_uri' => '/'
            ],
            'debug' => $this->config->get('debug')
            ]
        );

        $result = $this->isResponseOk($this->lastResponse);

        if ($result) {
            $path = (new \Purl\Url(
                $this->lastResponse->getHeader('Location')[0]
            ))->path;

            $this->path = substr($path, 0, strrpos($path, "/"));
        }

        return $result;
    }

    public function requestFullWebsiteBackup()
    {
        return $this->client->request(
            'POST',
            $this->createPath('backup/wizard-dofullbackup.html'),
            [
                'form_params' => [
                    'dest' => 'homedir',
                    'email_radio' => 0
                ],
                'debug' => $this->cpanelConfig->get('debug')
            ]
        );
    }

    public function requestDatabaseBackup()
    {
        return;
    }

    public function requestFileSystemBackup()
    {
        return;
    }

    /**
     * Verify the response code is acceptable.
     * 
     * @param \GuzzleHttp\Psr7\\Response $response
     * @return boolean
     */
    private function isResponseOk(Response $response)
    {
        return preg_match('/^(2|3)[0-9]{2}$/', $response->getStatusCode());
    }

    /**
     * Verifies required parameters are present in the config.
     * 
     * @param Config $config
     * @return boolean
     * @throws \InvalidArgumentException
     */
    private function setConfig(Config $config)
    {
        if (
            !$config->has('username')
            || !$config->has('password')
            || !$config->has('debug')
        ) {
            throw new \InvalidArgumentException('Config should have username, '
                . 'password, and debug settings');
        }

        return true;
    }

    /**
     *
     * @param string $method Http method.
     * @param string $path Url path segment.
     * @param array $args Http parameters as per GuzzleHttp
     * @return HttpResponse
     */
    private function request($method, $path, $args)
    {
        $args = array_merge(
            $args,
            ['debug' => $this->cpanelConfig->get('debug')]
        );

        $this->lastResponse = $this->client->request($method, $path, $args);

        return $this->lastResponse;
    }
}
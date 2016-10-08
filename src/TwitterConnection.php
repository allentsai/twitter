<?php

namespace Allen;

use GuzzleHttp\Client;

class TwitterConnection
{
    const GET = 'get';
    const BASE = 'https://api.twitter.com/1.1';

    private $client;
    private $url;
    private $params;
    private $auth;

    public function __construct(TwitterAuth $auth, Client $client = null)
    {
        $this->client = $client;
        $this->auth = $auth;
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        if (!isset($this->client)) {
            $this->client = new Client();
        }

        return $this->client;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param $resource
     *
     * @return mixed
     */
    public function get($resource)
    {
        return $this->getClient()->request(
            self::GET,
            self::BASE . $resource,
            [
                "headers" => [
                    "Authorization" => 'Bearer ' . $this->auth->getToken()
                ]
            ]
        );
    }
}


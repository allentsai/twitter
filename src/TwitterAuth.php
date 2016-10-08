<?php

namespace Allen;

use GuzzleHttp\Client;

class TwitterAuth
{
    private $token;
    private $secret;
    private $client;

    private $bearerToken;

    public function __construct($clientToken, $clientSecret, Client $client)
    {
        $this->token = $clientToken;
        $this->secret = $clientSecret;
        $this->client = $client;
    }

    private function getClient()
    {
        if (!isset($this->client))
        {
            $this->client = new Client();
        }

        return $this->client;
    }

    public function getToken()
    {
        if (empty($this->bearerToken))
        {
            $this->bearerToken = $this->retrieveBearerToken();
        }

        return $this->bearerToken;
    }

    private function retrieveBearerToken()
    {
        $string = $this->token . ":" . $this->secret;
        $encoded  = base64_encode($string);

        $response = $this->getClient()->post(
            'https://api.twitter.com/oauth2/token',
            [
                'auth' => [],
                'headers' => [
                    "Authorization" => 'Basic ' . $encoded,
                    "Content-Type" => 'application/x-www-form-urlencoded;charset=UTF-8'
                ],
                'body' => 'grant_type=client_credentials'
            ]
        );
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true)['access_token'];
    }
} 

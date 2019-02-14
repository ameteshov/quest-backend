<?php

namespace App\Util;

use GuzzleHttp\Client;

/**
 * @property Client $httpClient
 */
class OdnoklassnikiProvider
{
    const API_URL = 'https://api.ok.ru/fb.do';
    const AUTH_URL = 'https://connect.ok.ru/oauth/authorize?';
    const ACCESS_TOKEN_URL = 'https://api.ok.ru/oauth/token.do?';
    const APP_PERMISSIONS = [
        'VALUABLE_ACCESS', 'LONG_ACCESS_TOKEN', 'GET_EMAIL'
    ];

    protected $httpClient;
    protected $clientId;
    protected $clientSecret;
    protected $redirectUrl;

    public function __construct()
    {
        $this->httpClient = app(Client::class);

        $this->clientId = config('services.odnoklassniki.client_id');
        $this->clientSecret = config('services.odnoklassniki.client_secret');
        $this->redirectUrl = config('services.odnoklassniki.redirect');

        if (empty($this->clientId) || empty($this->clientSecret) || empty($this->redirectUrl)) {
            throw new \LogicException('Odnoklassniki social login config error');
        }
    }

    public function getRedirectUrl()
    {
        $scope = implode(';', self::APP_PERMISSIONS);

        return self::AUTH_URL . "client_id={$this->clientId}&redirect_uri={$this->redirectUrl}&scope={$scope}&response_type=code";
    }

    public function getUser(?string $code)
    {
        if (empty($code)) {
            throw new \InvalidArgumentException('Code was missed from odnoklassniki API');
        }

        $response = $this->httpClient->post(self::ACCESS_TOKEN_URL, [
            'json' => [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUrl,
                'grant_type' => 'authorization_code'
            ]
        ]);

        $responseJson = json_decode($response->getBody(), true);

        if (null === $responseJson) {
            throw new \JsonException('Unable to decode response from Odnoklassniki API');
        }

        $this->httpClient->get(self::API_URL, [
            'query' => [
                ''
            ]
        ]);
    }
}

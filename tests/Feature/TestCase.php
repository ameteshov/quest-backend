<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\Support\JsonFixturesTrait;
use Tests\Support\LoadDumpTrait;
use Tymon\JWTAuth\JWTAuth;

class TestCase extends \Tests\TestCase
{
    use LoadDumpTrait;
    use JsonFixturesTrait;

    protected $jwt;
    protected $authProvider;

    public function setUp(): void
    {
        parent::setUp();

        $this->authProvider = app(JWTAuth::class);

        $this->artisan('cache:clear');
        $this->artisan('migrate');

        $this->prepareDatabase();

        $this->getConnection()->beginTransaction();
    }

    public function tearDown(): void
    {
        $connection = $this->getConnection();

        $this->beforeApplicationDestroyed(function () use ($connection) {
            $connection->rollBack();

            $connection->disconnect();
        });

        parent::tearDown();
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
        $options = array_filter([
            'X-CSRF-TOKEN' => null,
            'Authorization' => empty($this->jwt) ? null : "Bearer {$this->jwt}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        $server = array_merge(
            $this->transformHeadersToServerVars($options),
            $server
        );

        return parent::call($method, $uri, $parameters, $cookies,
            $files, $server, $content);
    }

    public function actingAs(Authenticatable $user, $driver = null): self
    {
        $this->jwt = $this->authProvider->fromUser($user);

        return $this;
    }
}

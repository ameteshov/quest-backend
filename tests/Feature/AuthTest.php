<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
    }

    public function testAuth()
    {
        $response = $this->json('post', '/auth/login', [
            'email' => $this->admin->email,
            'password' => '111111'
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testAuthWrongPassword()
    {
        $response = $this->json('post', '/auth/login', [
            'email' => $this->admin->email,
            'password' => '111112'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthNotExists()
    {
        $response = $this->json('post', '/auth/login', [
            'email' => 'some@mail.ru',
            'password' => '111112'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testResetPassword()
    {
        $response = $this->json('post', '/auth/reset-password', [
            'email' => 'admin@example.net'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testResetWrongEmail()
    {
        $response = $this->json('post', '/auth/reset-password', [
            'email' => 'some@mail'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testResetNotExists()
    {
        $response = $this->json('post', '/auth/reset-password', [
            'email' => 'some@mail.to'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testRefreshToken()
    {
        $response = $this->actingAs($this->admin)->json('get', '/auth/refresh');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNotEmpty(
            $response->headers->get('authorization')
        );

        $auth = $response->headers->get('authorization');

        $explodedHeader = explode(' ', $auth);

        $this->assertNotEquals($this->jwt, last($explodedHeader));
    }

    public function testRegister()
    {
        $requestData = [
            'email' => 'test@test.test',
            'name' => 'ighn ndld',
            'password' => '111111',
            'confirm' => '111111'
        ];

        $response = $this->json('post', '/auth/register', $requestData);

        $this->assertDatabaseHas('users', array_except($requestData, ['password', 'confirm']));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testRegisterEmailExists()
    {
        $requestData = [
            'email' => 'ivan.dubinov@example.net',
            'name' => 'ighn ndld',
            'password' => '111111',
            'confirm' => '111111'
        ];

        $response = $this->json('post', '/auth/register', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @dataProvider getRegisterRequestData
     *
     * @var array $requestData
     **/
    public function testRegisterWrongData($requestData)
    {
        $response = $this->json('post', '/auth/register', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getRegisterRequestData()
    {
        return [
            [
                'request_data' => [
                    'name' => 'ighn ndld',
                    'password' => '111111',
                    'confirm' => '111111'
                ]
            ],
            [
                'request_data' => [
                    'email' => 'ivan@example.net',
                    'password' => '111111',
                    'confirm' => '111111'
                ]
            ],
            [
                'request_data' => [
                    'email' => 'ivan@example.net',
                    'name' => 'ighn ndld',
                    'confirm' => '111111'
                ]
            ],
            [
                'request_data' => [
                    'email' => 'ivan@example.net',
                    'name' => 'ighn ndld',
                    'password' => '111111',
                ]
            ],
            [
                'request_data' => [
                    'email' => 'ivan@example.net',
                    'name' => 'ighn ndld',
                    'password' => '111111',
                    'confirm' => '111111222'
                ]
            ]
        ];
    }
}

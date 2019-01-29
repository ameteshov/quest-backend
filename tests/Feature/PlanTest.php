<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class PlanTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testCreatePurchasePlan()
    {
        $data = $this->getJsonFixture('plan.json');

        $response = $this->actingAs($this->admin)->json('post', '/plans', $data);

        $response->assertStatus(Response::HTTP_OK);

        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas('plans', $data);
    }

    public function testCreateSubscriptionPlan()
    {
        $data = $this->getJsonFixture('subscription.json');

        $response = $this->actingAs($this->admin)->json('post', '/plans', $data);

        $response->assertStatus(Response::HTTP_OK);

        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas('plans', $data);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('plan.json');

        $response = $this->actingAs($this->user)->json('post', '/plans', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('plan.json');

        $response = $this->json('post', '/plans', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider getInvalidPostRequestData
     */
    public function testCreateInvalidRequest($data)
    {
        $response = $this->actingAs($this->admin)->json('post', '/plans', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', '/plans/1');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('get_plan.json'));
    }

    public function testGetInactiveAsUser()
    {
        $response = $this->actingAs($this->user)->json('get', '/plans/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNotExistsAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('get', '/plans/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNotExistsAsUser()
    {
        $response = $this->actingAs($this->admin)->json('get', '/plans/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNoAuth()
    {
        $response = $this->json('get', '/plans/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'update name',
            'description' => [
                'line 1 updated',
                'line 2 updated',
                'line 3 added'
            ],
            'points' => 20,
            'price' => 10000,
            'is_active' => 0
        ];

        $response = $this->actingAs($this->admin)->json('put', '/plans/1', $data);

        $data['description'] = json_encode($data['description']);

        $this->assertDatabaseHas('plans', array_merge($data, ['id' => 1]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNoPermission()
    {
        $response = $this->actingAs($this->user)->json('put', '/plans/1', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateNoExists()
    {
        $response = $this->actingAs($this->admin)->json('put', '/plans/0', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth()
    {
        $response = $this->json('put', '/plans/1', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider getInvalidPutRequestData
     */
    public function testUpdateInvalidRequest($data)
    {
        $response = $this->actingAs($this->admin)->json('put', '/plans/1', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/plans/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('plans', ['id' => 1]);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/plans/1');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/plans/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/plans/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSearchAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('get', '/plans', []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson($this->getJsonFixture('search_admin.json'));
    }

    public function testSearchAsUser()
    {
        $response = $this->actingAs($this->user)->json('get', '/plans', []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson($this->getJsonFixture('search_user.json'));
    }

    public function testSearchNoAuth()
    {
        $response = $this->json('get', '/plans', []);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function getInvalidPutRequestData()
    {
        return [
            [
                'data' => [
                    'name' => 12
                ]
            ],
            [
                'data' => [
                    'name' => 'medium',
                    'description' => 'string',
                    'price' => 'string',
                    'points' => 'string',
                    'is_active' => 'string'
                ]
            ]
        ];
    }

    public function getInvalidPostRequestData()
    {
        return [
            [
                'data' => []
            ],
            [
                'data' => [
                    'name' => 'medium',
                    'description' => ['test'],
                    'is_active' => 1,
                    'type' => 'purchase'
                ]
            ],
            [
                'data' => [
                    'name' => 123,
                    'description' => 'string',
                    'price' => 'string',
                    'points' => 'string',
                    'is_active' => 'string',
                    'type' => 'some another'
                ]
            ]
        ];
    }
}

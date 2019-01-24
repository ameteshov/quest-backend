<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class EmployeeCharacteristicTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testCreate()
    {
        $data = $this->getJsonFixture('create.json');

        $response = $this->actingAs($this->admin)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment($data);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create.json');

        $response = $this->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('create.json');

        $response = $this->actingAs($this->user)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateNameExists()
    {
        $data = $this->getJsonFixture('create.json');
        $data['email'] = $this->user->email;

        $response = $this->actingAs($this->admin)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateAsAdmin()
    {
        $data = ['name' => 'new name'];

        $response = $this->actingAs($this->admin)->json('put', "/users/{$this->user->id}", $data);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('users',
            array_merge(['id' => $this->user->id], $data)
        );
    }

    public function testUpdateNoAuth()
    {
        $data = ['name' => 'new name'];

        $response = $this->json('put', "/users/{$this->user->id}", $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateNoPermission()
    {
        $data = ['name' => 'new name'];

        $response = $this->actingAs($this->user)->json('put', "/users/{$this->admin->id}", $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateNotExistsAsAdmin()
    {
        $data = ['name' => 'new name'];

        $response = $this->actingAs($this->user)->json('put', "/users/0", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateInvalidData()
    {
        $data = [
            'name' => 1,
            'email' => 'fail',
            'questionnaires_count' => 'string',
            'is_active' => 'string'
        ];

        $response = $this->actingAs($this->admin)->json('put', "/users/2", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email', 'name', 'questionnaires_count', 'is_active']);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/2');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/users/2');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/users/2');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider getFilters
     * @param $filters
     * @param $fixture
     */
    public function testSearch($fixture, $filters)
    {
        $response = $this->actingAs($this->admin)->json('get', '/users', $filters);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson($this->getJsonFixture($fixture));
    }

    public function getFilters()
    {
        return [
            [
                'fixture' => 'no_filters.json',
                'filters' => []
            ],
            [
                'fixture' => 'get_all.json',
                'filters' => ['all' => 1]
            ]
        ];
    }
}

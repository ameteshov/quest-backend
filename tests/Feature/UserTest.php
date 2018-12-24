<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class UserTest extends TestCase
{
    protected $admin;
    protected $user;

    //TODO:: add search tests
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

    public function testCreateEmailExists()
    {
        $data = $this->getJsonFixture('create.json');
        $data['email'] = $this->user->email;

        $response = $this->actingAs($this->admin)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @dataProvider getInvalidDataCreateRequest
     */
    public function testCreateInvalidData($requestData)
    {
        $response = $this->actingAs($this->admin)->json('post', '/users', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate()
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

    public function testUpdateNotExists()
    {

    }

    public function testUpdateInvalidData()
    {

    }

    public function testUpdateStatusNoPermission()
    {

    }

    public function testDelete()
    {

    }

    public function testDeleteNoAuth()
    {

    }

    public function testDeleteNoPermission()
    {

    }

    public function testDeleteNotExists()
    {

    }

    public function testSearch()
    {

    }

    public function getInvalidDataCreateRequest()
    {
        return [
            ['request_data' => []],
            ['request_data' => ['email' => 'test']]
        ];
    }
}

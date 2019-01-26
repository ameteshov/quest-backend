<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class QuestionnaireTypeTest extends TestCase
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
        $data = $this->getJsonFixture('type.json');

        $response = $this->actingAs($this->admin)->json('post', '/questionnaires/types', $data);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('questionnaire_types', $data);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('type.json');

        $response = $this->actingAs($this->user)->json('post', '/questionnaires/types', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('type.json');

        $response = $this->json('post', '/questionnaires/types', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider getInvalidPostRequestData
     * @param $requestData
     */
    public function testCreateInvalidRequest($requestData)
    {
        $response = $this->actingAs($this->admin)->json('post', '/questionnaires/types', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/types/1');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('get_type.json'));
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/types/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNoAuth()
    {
        $response = $this->json('get', '/questionnaires/types/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'new name'
        ];

        $response = $this->actingAs($this->admin)->json('put', '/questionnaires/types/1', $data);

        $this->assertDatabaseHas('questionnaire_types', array_merge($data, ['id' => 1]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNoPermission()
    {
        $response = $this->actingAs($this->user)->json('put', '/questionnaires/types/1', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateNoExists()
    {
        $response = $this->actingAs($this->admin)->json('put', '/questionnaires/types/0', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth()
    {
        $response = $this->json('put', '/questionnaires/types/1', [
            'name' => 'test'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider getInvalidPutRequestData
     */
    public function testUpdateInvalidRequest($data)
    {
        $response = $this->actingAs($this->admin)->json('put', '/questionnaires/types/1', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/questionnaires/types/5');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('questionnaire_types', ['id' => 5]);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/questionnaires/types/1');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/questionnaires/types/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteHasRelations()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/questionnaires/types/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/questionnaires/types/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSearch()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/types', []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson($this->getJsonFixture('search_no_filters.json'));
    }

    public function testSearchNoAuth()
    {
        $response = $this->json('get', '/questionnaires/types', []);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function getInvalidPostRequestData()
    {
        return [
            [
                'request_data' => []
            ],
            [
                'request_data' => ['name' => 'agility']
            ]
        ];
    }

    public function getInvalidPutRequestData()
    {
        return [
            [
                'request_data' => [
                    'name' => 12
                ]
            ],
            [
                'request_data' => [
                    'name' => 'agility'
                ]
            ],
            [
                'request_data' => []
            ]
        ];
    }
}

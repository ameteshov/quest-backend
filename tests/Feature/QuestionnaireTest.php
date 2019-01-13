<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class QuestionnaireTest extends TestCase
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

        $response = $this->actingAs($this->admin)->json('post', '/questionnaires', $data);

        $response->assertStatus(Response::HTTP_OK);
        $data['content'] = json_encode($data['content']);

        $this->assertDatabaseHas('questionnaires', $data);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('create.json');

        $response = $this->actingAs($this->user)->json('post', '/questionnaires', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create.json');

        $response = $this->json('post', '/questionnaires', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider getCreateRequestData
     * @var array $requestData
     * */
    public function testCreateWrongData($requestData)
    {
        $response = $this->actingAs($this->admin)->json('post', '/questionnaires', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate()
    {
        $response = $this->actingAs($this->admin)->json('put', '/questionnaires/1', [
            'name' => 'new name',
            'content' => ['test' => 1],
            'is_active' => 0
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('questionnaires', [
            'id' => 1,
            'name' => 'new name',
            'content' => json_encode(['test' => 1]),
            'is_active' => 0
        ]);
    }

    public function testUpdateNoPermissions()
    {
        $response = $this->actingAs($this->user)->json('put', '/questionnaires/1', [
            'name' => 'new name',
            'content' => ['test' => 1]
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateNoAuth()
    {
        $response = $this->json('put', '/questionnaires/1', [
            'name' => 'new name',
            'content' => ['test' => 1]
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateWrongData()
    {
        $response = $this->actingAs($this->admin)->json('put', '/questionnaires/1', [
            'name' => 123412,
            'content' => 'some string'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/1');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('survey.json'));
    }

    public function testGetWithResultsAsUser()
    {
        $response = $this->actingAs($this->user)->json('get', '/questionnaires/1', ['with' => ['results']]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('user_get_survey_with_results.json'));
    }

    public function testGetWithResultsAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/1', ['with' => ['results']]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('admin_get_survey_with_results.json'));
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNotAvailableAsUser()
    {
        $response = $this->actingAs($this->user)->json('get', '/questionnaires/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetNoAuth()
    {
        $response = $this->json('get', '/questionnaires/2');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider  getSearchFilters
     */
    public function testSearch($filters, $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', '/questionnaires', $filters);

        $response->assertExactJson($this->getJsonFixture($fixture));
    }

    public function getCreateRequestData()
    {
        return [
            [
                'request_data' => [
                    'name' => 123412,
                    'content' => 'some string'
                ]
            ],
            [
                'request_data' => [
                    'content' => ['key' => 'value']
                ]
            ],
            [
                'request_data' => [
                    'name' => 'key'
                ]
            ]
        ];
    }

    public function getSearchFilters()
    {
        return [
            [
                'filters' => [],
                'fixture' => 'search_no_filters.json'
            ],
            [
                'filters' => ['all' => 1],
                'fixture' => 'search_all.json'
            ]
        ];
    }
}

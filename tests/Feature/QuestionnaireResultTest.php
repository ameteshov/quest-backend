<?php

namespace Tests\Feature;

use App\Model\QuestionnaireResult;
use App\Model\User;
use Illuminate\Http\Response;

class QuestionnaireResultTest extends TestCase
{
    protected $admin;
    protected $userUnableSend;
    protected $userAbleSend;
    protected $form;
    protected $submittedForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->userUnableSend = User::find(2);
        $this->userAbleSend = User::find(4);
        $this->submittedForm = QuestionnaireResult::find(1);
        $this->form = QuestionnaireResult::find(3);
    }

    public function testSend()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userAbleSend)->json('post', '/api/questionnaires/1/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('questionnaires_results', [
            'email' => $data['email'],
            'recipient_name' => $data['name'],
            'questionnaire_id' => 1
        ]);
    }

    public function testSendLimitExceed()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userUnableSend)->json('post', '/api/questionnaires/1/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSendNoAuth()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->json('post', '/api/questionnaires/1/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSendNoQuestionnaire()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userAbleSend)->json('post', '/api/questionnaires/0/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testSendInvalidRequest($data)
    {
        $response = $this->actingAs($this->userAbleSend)->json('post', '/api/questionnaires/1/send', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGetByHash()
    {
        $response = $this->json('get', "api/forms/{$this->form->access_hash}");

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson($this->getJsonFixture('form.json'));
    }

    public function testGetByHashFormSubmitted()
    {
        $response = $this->json('get', "api/forms/{$this->submittedForm->access_hash}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetByHashNotExists()
    {
        $response = $this->json('get', "api/form/0192");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

//    public function testSubmit()
//    {
//        $request = $this->json('post', "api/form/{$this->form->access_hash}", [
//            ''
//        ]);
//    }

    public function getInvalidData()
    {
        return [
            ['data' => []],
            [
                'data' => [
                    'list' => []
                ]
            ],
            [
                'data' => [
                    'list' => [
                        ['name' => 'test']
                    ]
                ]
            ],
            [
                'data' => [
                    'list' => [
                        ['email' => 'test@test.test']
                    ]
                ]
            ],
            [
                'data' => [
                    'list' => [
                        [
                            'name' => 'name',
                            'email' => 'test@test.test'
                        ],
                        [
                            'name' => 'name',
                            'email' => 'test'
                        ]
                    ]
                ]
            ],
        ];
    }
}
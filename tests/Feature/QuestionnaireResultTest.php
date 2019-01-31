<?php

namespace Tests\Feature;

use App\Model\Questionnaire;
use App\Model\QuestionnaireResult;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class QuestionnaireResultTest extends TestCase
{
    protected $admin;
    protected $userUnableSend;
    protected $userAbleSend;
    protected $userSubscriptionExpired;
    protected $submittedForm;
    protected $expiredForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->userUnableSend = User::find(2);
        $this->userAbleSend = User::find(4);
        $this->userSubscriptionExpired = User::find(3);
        $this->submittedForm = QuestionnaireResult::find(1);
        $this->expiredForm = QuestionnaireResult::find(3);
    }

    public function testSend()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userAbleSend)->json('post', '/questionnaires/1/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('questionnaires_results', [
            'email' => $data['email'],
            'recipient_name' => $data['name'],
            'questionnaire_id' => 1
        ]);
    }

    public function testSendUserHasSubscription()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userAbleSend)->json('post', '/questionnaires/1/send', [
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

        $response = $this->actingAs($this->userUnableSend)->json('post', '/questionnaires/1/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSendSubscriptionExpired()
    {
        $data = [
            'email' => 'vasya@mail.test',
            'name' => 'vasya ignatiy'
        ];

        $response = $this->actingAs($this->userSubscriptionExpired)->json('post', '/questionnaires/1/send', [
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

        $response = $this->json('post', '/questionnaires/1/send', [
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

        $response = $this->actingAs($this->userAbleSend)->json('post', '/questionnaires/0/send', [
            'list' => [$data]
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testSendInvalidRequest($data)
    {
        $response = $this->actingAs($this->userAbleSend)->json('post', '/questionnaires/1/send', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGetByHash()
    {
        $form = $this->loadTestFixture(QuestionnaireResult::class, 'available_form.json');
        $questionnaire = Questionnaire::find(1);;

        $response = $this->json('get', "/forms/{$form->access_hash}");

        $response->assertStatus(Response::HTTP_OK);

        $questionnaire['results'] = [$form->toArray()];

        $response->assertExactJson($questionnaire->toArray());
    }

    public function testGetByHashFormSubmitted()
    {
        $response = $this->json('get', "/forms/{$this->submittedForm->access_hash}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetByHashFormExpired()
    {
        $response = $this->json('get', "/forms/{$this->submittedForm->access_hash}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testGetByHashNotExists()
    {
        $response = $this->json('get', '/form/0192');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testSubmit()
    {
        $form = $this->loadTestFixture(QuestionnaireResult::class, 'available_form.json');
        $data = [
            'hash' => $form->access_hash,
            'content' => [
                [
                    'index' => 0,
                    'result' => 2
                ],
                [
                    'index' => 1,
                    'result' => 4
                ],
            ],
            'info' => [
                'phone' => '123',
                'birthday' => '25.11.2010',
                'name' => 'Diego Nahuyalize'
            ]
        ];

        $response = $this->json('post', "/forms", $data);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('questionnaires_results', [
            'access_hash' => $data['hash'],
            'content' => json_encode($data['content']),
            'recipient_phone' => $data['info']['phone'],
            'recipient_name' => $data['info']['name'],
            'birthday_date' => Carbon::parse($data['info']['birthday'])->toDateString(),
            'score' => 6
        ]);
    }

    public function testSubmitFormExpired()
    {
        $data = [
            'hash' => $this->expiredForm->access_hash,
            'content' => [
                [
                    'index' => 0,
                    'result' => 2
                ],
                [
                    'index' => 1,
                    'result' => 4
                ],
            ],
            'info' => [
                'phone' => '123',
                'birthday' => '25.11.2010',
                'name' => 'Diego Nahuyalize'
            ]
        ];

        $response = $this->json('post', "/forms", $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSubmitFormSubmitted()
    {
        $data = [
            'hash' => $this->submittedForm->access_hash,
            'content' => [
                [
                    'index' => 0,
                    'result' => 2
                ],
                [
                    'index' => 1,
                    'result' => 4
                ],
            ],
            'info' => [
                'phone' => '123',
                'birthday' => '25.11.2010',
                'name' => 'Diego Nahuyalize'
            ]
        ];

        $response = $this->json('post', "/forms", $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSubmitFormNotExists()
    {
        $data = [
            'hash' => 'wowoedowedwoekmd',
            'content' => [
                [
                    'index' => 0,
                    'result' => 2
                ],
                [
                    'index' => 1,
                    'result' => 4
                ],
            ],
            'info' => [
                'phone' => '123',
                'birthday' => '25.11.2010',
                'name' => 'Diego Nahuyalize'
            ]
        ];

        $response = $this->json('post', "/forms", $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

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

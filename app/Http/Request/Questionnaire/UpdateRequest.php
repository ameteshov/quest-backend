<?php

namespace App\Request\Questionnaire;

use App\Model\Role;
use App\Request\Request;
use App\Service\QuestionnaireService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRequest
 * @package App\Request\Questionnaire
 * @property QuestionnaireService $service
 */
class UpdateRequest extends Request
{
    protected $service;

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->service = app(QuestionnaireService::class);
    }

    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN || $this->isUserOwner();
    }

    public function rules()
    {
        return [
            'name' => 'string',
            'content' => 'array',
            'content.questions' => 'array',
            'content.questions.*.text' => 'required|string',
            'content.answers' => 'array',
            'content.answers.*.text' => 'required|string',
            'content.answers.*.points' => 'required|numeric',
            'success_score' => 'integer',
            'type_id' => 'integer|nullable|exists:questionnaire_types,id',
            'description' => 'string'
        ];
    }

    public function validateResolved()
    {
        if (!$this->service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('Questionnaire not found');
        }

        parent::validateResolved();
    }

    protected function isUserOwner()
    {
        return $this->service->exists([
            'id' => $this->route('id'),
            'user_id' => $this->user()->id
        ]);
    }
}

<?php

namespace App\Request\Questionnaire;

use App\Request\Request;
use App\Service\QuestionnaireService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'list' => 'required|array',
            'list.*.email' => 'string|email',
            'list.*.name' => 'string|required'
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(QuestionnaireService::class);
        $questionnaire = [
            'id' => $this->route('id'),
            'is_active' => true
        ];

        if (!$service->exists($questionnaire)) {
            throw new NotFoundHttpException('Questionnaire not found');
        }

        if ($service->isLimitExceeded($this->user()->id, $this->getListCount())) {
            throw new BadRequestHttpException('You can not sent questionnaires anymore, please upgrade plan');
        }
    }

    protected function getListCount()
    {
        return count($this->input('list'));
    }
}

<?php

namespace App\Request\Questionnaire;

use App\Model\Role;
use App\Request\Request;
use App\Service\QuestionnaireResultService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubmitRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hash' => 'required|string',
            'content' => 'required|array',
            'phone' => 'required|string'
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(QuestionnaireResultService::class);

        if (!$service->isAvailable($this->input('hash'))) {
            throw new BadRequestHttpException('Form not exists or already was submitted');
        }
    }
}

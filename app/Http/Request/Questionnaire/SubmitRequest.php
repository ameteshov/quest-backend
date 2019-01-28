<?php

namespace App\Request\Questionnaire;

use App\Request\Request;
use App\Service\QuestionnaireService;
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
            'content.*.index' => 'required|integer',
            'content.*.result' => 'required',
            'info' => 'required|array',
            'info.phone' => 'required|string',
            'info.email' => 'string|nullable|email',
            'info.name' => 'required|string',
            'info.birthday' => 'required|date',
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(QuestionnaireService::class);

        if (!$service->isAvailableForRecipient($this->input('hash'))) {
            throw new BadRequestHttpException('Form not exists or already was submitted');
        }
    }
}

<?php

namespace App\Request\QuestionnaireResult;

use App\Request\Request;
use App\Service\QuestionnaireResultService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        $service = app(QuestionnaireResultService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('Form not found');
        }
    }
}

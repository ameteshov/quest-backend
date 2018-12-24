<?php

namespace App\Request\Questionnaire;

use App\Request\Request;
use App\Service\QuestionnaireService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetByHashRequest extends Request
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
        parent::validateResolved();

        $service = app(QuestionnaireService::class);

        if (null === $service->findByHash($this->route('hash'))) {
            throw new NotFoundHttpException();
        }
    }
}

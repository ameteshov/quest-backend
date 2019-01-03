<?php

namespace App\Request\Questionnaire;

use App\Request\Request;
use App\Service\QuestionnaireService;
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
        $service = app(QuestionnaireService::class);

        if (!$service->existsAndAvailable(
            $this->route('id'),
            $this->user()->role_id
        )) {
            throw new NotFoundHttpException('Survey not exists');
        }
    }
}

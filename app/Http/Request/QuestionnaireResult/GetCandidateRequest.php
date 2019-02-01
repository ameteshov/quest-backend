<?php

namespace App\Request\QuestionnaireResult;

use App\Request\Request;
use App\Service\QuestionnaireResultService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetCandidateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string'
        ];
    }

    public function validateResolved()
    {
        $service = app(QuestionnaireResultService::class);

        if (!$service->exists([
            'email' => $this->input('email'),
            'user_id' => $this->user()->id
        ])) {
            throw new NotFoundHttpException('Candidate not found');
        }
    }
}

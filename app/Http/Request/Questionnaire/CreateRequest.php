<?php

namespace App\Request\Questionnaire;

use App\Model\Questionnaire;
use App\Model\Role;
use App\Request\Request;

class CreateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'content' => 'required|array',
            'content.questions' => 'required|array',
            'content.questions.*.text' => 'required|string',
            'content.answers' => 'required|array',
            'content.answers.*.text' => 'required|string',
            'content.answers.*.points' => 'required|numeric',
            'success_score' => 'integer',
            'type_id' => 'integer|nullable|exists:questionnaire_types,id',
            'description' => 'string'
        ];
    }
}

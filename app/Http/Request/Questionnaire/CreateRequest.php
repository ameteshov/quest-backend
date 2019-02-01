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
            'content.answers' => 'required_with:type_id|array',
            'content.answers.*.text' => 'required_with:type_id|string',
            'content.answers.*.points' => 'required_with:type_id|numeric',
            'success_score' => 'integer',
            'type_id' => 'integer|nullable|exists:questionnaire_types,id',
            'description' => 'string'
        ];
    }
}

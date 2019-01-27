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
            'success_score' => 'integer',
            'result_type' => 'string|in:' . implode(',', [Questionnaire::SUM_TYPE, Questionnaire::AVG_TYPE]),
            'type_id' => 'integer|nullable|exists:questionnaire_types,id',
            'description' => 'string'
        ];
    }
}

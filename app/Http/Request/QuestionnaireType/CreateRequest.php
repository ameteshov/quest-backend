<?php

namespace App\Request\QuestionnaireType;

use App\Model\Questionnaire;
use App\Model\Role;
use App\Request\Request;

class CreateRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:questionnaire_types,name'
        ];
    }
}

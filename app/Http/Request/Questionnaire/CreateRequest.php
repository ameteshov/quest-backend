<?php

namespace App\Request\Questionnaire;

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
            'name' => 'required|string',
            'content' => 'required|array'
        ];
    }
}

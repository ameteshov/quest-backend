<?php

namespace App\Request\User;

use App\Model\Role;
use App\Request\Request;

class SearchRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN;
    }

    public function rules()
    {
        return [
            'page' => 'integer'
        ];
    }
}

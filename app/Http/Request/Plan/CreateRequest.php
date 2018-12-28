<?php

namespace App\Request\Plan;

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
            'name' => 'required|string|unique:plans,name',
            'price' => 'required|numeric',
            'points' => 'required|integer',
            'description' => 'required|array',
            'is_active' => 'required|boolean'
        ];
    }
}

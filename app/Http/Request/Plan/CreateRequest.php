<?php

namespace App\Request\Plan;

use App\Model\Plan;
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
            'price' => 'required_if:type,' . Plan::PURCHASE_TYPE . '|numeric',
            'points' => 'required_if:type,' . Plan::PURCHASE_TYPE . '|integer|nullable',
            'type' => 'required|string|in:' . implode(',', [Plan::SUB_TYPE, Plan::PURCHASE_TYPE]),
            'description' => 'required|array',
            'is_active' => 'required|boolean'
        ];
    }
}

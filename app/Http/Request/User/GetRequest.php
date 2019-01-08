<?php

namespace App\Request\User;

use App\Model\Role;
use App\Request\Request;

class GetRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN ||
            $this->user()->id === (int)$this->route('id');
    }

    public function rules()
    {
        return [];
    }
}

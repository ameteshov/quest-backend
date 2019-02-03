<?php

namespace App\Request\User;

use App\Model\Role;
use App\Request\Request;

class GetProfileRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}

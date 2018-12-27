<?php

namespace App\Request\User;

use App\Request\Request;

class GetRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

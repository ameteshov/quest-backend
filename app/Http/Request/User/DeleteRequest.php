<?php

namespace App\Request\User;

use App\Request\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

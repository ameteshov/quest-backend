<?php

namespace App\Request\Payment;

use App\Request\Request;

class GetRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

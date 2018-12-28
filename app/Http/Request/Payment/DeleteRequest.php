<?php

namespace App\Request\Payment;

use App\Request\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

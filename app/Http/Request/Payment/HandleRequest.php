<?php

namespace App\Request\Payment;

use App\Request\Request;

class HandleRequest extends Request
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

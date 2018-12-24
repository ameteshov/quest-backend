<?php

namespace App\Request\Questionnaire;

use App\Request\Request;

class GetRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

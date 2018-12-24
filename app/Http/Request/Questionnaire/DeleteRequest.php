<?php

namespace App\Request\Questionnaire;

use App\Request\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return false;
    }
}

<?php

namespace App\Request\QuestionnaireResult;

use App\Request\Request;

class GetVacanciesRequest extends Request
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

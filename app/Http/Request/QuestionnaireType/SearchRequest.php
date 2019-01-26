<?php

namespace App\Request\QuestionnaireType;

use App\Model\Role;
use App\Request\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'all' => 'boolean|nullable',
            'page' => 'number|nullable'
        ];
    }
}

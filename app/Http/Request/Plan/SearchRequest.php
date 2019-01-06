<?php

namespace App\Request\Plan;

use App\Model\Role;
use App\Request\Request;

class SearchRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'integer',
            'all' => 'boolean',
            'is_active' => 'boolean'
        ];
    }
}

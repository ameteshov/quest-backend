<?php

namespace App\Request\Questionnaire;

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
            'page' => 'number|nullable',
            'active' => 'boolean|nullable'
        ];
    }

    public function validateResolved()
    {
        if ($this->isUnableFilterActive()) {
            throw new BadRequestHttpException();
        }
    }

    protected function isUnableFilterActive()
    {
        return $this->user()
            && $this->user()->role_id !== Role::ROLE_ADMIN
            && $this->input('active');
    }
}

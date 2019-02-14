<?php

namespace App\Request;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginSocialRequest extends Request
{
    protected $availableProviders = ['vkontakte', 'google', 'twitter', 'facebook', 'odnoklassniki'];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        if (!in_array($this->route('provider'), $this->availableProviders)) {
            throw new BadRequestHttpException('Provider value is incorrect');
        }
    }
}

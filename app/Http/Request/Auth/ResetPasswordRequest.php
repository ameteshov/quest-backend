<?php

namespace App\Request;

use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResetPasswordRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email'
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists(['email' => $this->input('email')])) {
            throw new NotFoundHttpException("User with email {$this->input('email')} does not exists");
        }
    }
}

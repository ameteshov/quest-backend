<?php

namespace App\Request\Payment;

use App\Model\Role;
use App\Request\Request;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN ||
            $this->route('id') === $this->user()->id;
    }

    public function rules()
    {
        return [
            'name' => 'string',
            'email' => 'string|email|unique:users,email,' . $this->route('id')
        ];
    }

    public function validateResolved()
    {
        $service = app(UserService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('User not exists');
        }

        parent::validateResolved();
    }
}

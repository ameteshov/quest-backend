<?php

namespace App\Request\User;

use App\Model\Role;
use App\Request\Request;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('User not exists');
        }

        if ($this->user()->id === (int)$this->route('id')) {
            throw new BadRequestHttpException('You are not able to delete yourself');
        }
    }
}

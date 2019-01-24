<?php

namespace App\Request\EmployeeCharacteristic;

use App\Model\Role;
use App\Request\Request;
use App\Service\PlanService;
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

        $service = app(PlanService::class);

        if (!$service->exists(
            $this->route('id'),
            $this->user()->toArray())
        ) {
            throw new NotFoundHttpException("Plan does not exist");
        }
    }
}

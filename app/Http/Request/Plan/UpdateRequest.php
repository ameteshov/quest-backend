<?php

namespace App\Request\Plan;

use App\Model\Role;
use App\Request\Request;
use App\Service\PlanService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN;
    }

    public function rules()
    {
        return [
            'name' => 'string|unique:plans,name,' . $this->route('id'),
            'price' => 'numeric',
            'points' => 'integer',
            'description' => 'array',
            'is_active' => 'boolean'
        ];
    }

    public function validateResolved()
    {
        $service = app(PlanService::class);

        if (!$service->exists($this->route('id'), $this->user()->toArray())) {
            throw new NotFoundHttpException('Plan not exists');
        }

        parent::validateResolved();
    }
}

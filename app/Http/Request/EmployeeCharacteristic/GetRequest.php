<?php

namespace App\Request\EmployeeCharacteristic;

use App\Request\Request;
use App\Service\PlanService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        $service = app(PlanService::class);

        if (!$service->exists(
            $this->route('id'),
            $this->user()->toArray())
        ) {
            throw new NotFoundHttpException("Plan does not exist");
        }
    }
}

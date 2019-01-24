<?php

namespace App\Service;

use App\Repository\EmployeeCharacteristicRepository;

/**
 * @property EmployeeCharacteristicRepository $repository
 * */
class EmployeeCharacteristicService extends Service
{
    public function __construct()
    {
        $this->setRepository(EmployeeCharacteristicRepository::class);
    }
}

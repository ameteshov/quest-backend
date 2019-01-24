<?php

namespace App\Repository;

use App\Model\EmployeeCharacteristic;

class EmployeeCharacteristicRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(EmployeeCharacteristic::class);
    }
}

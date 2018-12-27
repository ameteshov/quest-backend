<?php

namespace App\Repository;

use App\Model\Plan;

class PlanRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Plan::class);
    }
}

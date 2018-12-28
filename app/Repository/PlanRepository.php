<?php

namespace App\Repository;

use App\Model\Plan;

class PlanRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Plan::class);
    }

    public function search(array $filters)
    {
        return $this->getSearchQuery($filters)
            ->filterBy('is_active')
            ->getResult();
    }
}

<?php

namespace App\Repository;

use App\Model\Payment;

class PaymentRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Payment::class);
    }

    public function search(?array $filters = []): array
    {
        return $this->getSearchQuery($filters)
            ->with()
            ->getResult();
    }
}

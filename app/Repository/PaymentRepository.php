<?php

namespace App\Repository;

use App\Model\Payment;

class PaymentRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Payment::class);
    }
}

<?php

namespace App\Repository;

use App\Model\PaymentTransaction;

class PaymentTransactionRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(PaymentTransaction::class);
    }
}

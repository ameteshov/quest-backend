<?php
/**
 * Created by PhpStorm.
 * User: ameteshov
 * Date: 27.12.18
 * Time: 17:41
 */

namespace App\Support\Interfaces;


interface PaymentClientInterface
{
    public function create(array $paymentData): array;

    public function refund(): array;

    public function get(): array;
}

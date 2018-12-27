<?php

namespace App\Service;

use App\Exceptions\UnableTransactionCreationException;
use App\Repository\PaymentRepository;
use App\Support\Interfaces\PaymentClientInterface;

/**
 * @property PaymentRepository $repository
 * @property PaymentClientInterface $paymentClient
 * @property PaymentTransactionService $paymentTransactionService
 * */
class PaymentService extends Service
{
    protected $paymentClient;
    protected $paymentTransactionService;

    public function __construct(PaymentClientInterface $paymentClient)
    {
        $this->setRepository(PaymentRepository::class);

        $this->paymentClient = $paymentClient;
        $this->paymentTransactionService = app(PaymentTransactionService::class);
    }

    public function create(int $userId, int $planId)
    {
        try {
            $paymentData = $this->paymentTransactionService->begin($userId, $planId);

            $payment = $this->paymentClient->create($paymentData);

            $this->repository->create(
                array_merge($payment['entity'], ['user_id' => $userId])
            );

            return array_only($payment, 'url');
        } catch (UnableTransactionCreationException $e) {

        }
    }

    public function finishPayment($paymentData): void
    {
        //
    }
}

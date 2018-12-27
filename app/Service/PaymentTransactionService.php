<?php

namespace App\Service;

use App\Exceptions\UnableTransactionCreationException;
use App\Repository\PaymentTransactionRepository;
use App\Repository\PlanRepository;
use Ramsey\Uuid\Uuid;

/**
 * @property PaymentTransactionRepository $repository
 * @property PlanRepository $planRepository
 * */
class PaymentTransactionService extends Service
{
    protected $planRepository;

    public function __construct(PaymentTransactionRepository $repository, PlanRepository $planRepository)
    {
        $this->repository = $repository;
        $this->planRepository = $planRepository;
    }

    public function begin(int $userId, int $planId): array
    {
        $transaction = $this->repository->findBy(['user_id' => $userId], ['plan']);

        if ([] === $transaction) {
            $plan = $this->planRepository->find($planId);

            $transaction = $this->repository->create([
                'user_id' => $userId,
                'plan_id' => $plan['id'],
                'token' => Uuid::uuid4()
            ]);

            return [
                'amount' => $plan['price'],
                'idempotent_key' => $transaction['token']
            ];
        }

        if (array_get($transaction, 'plan.id') !== $planId) {
            throw new UnableTransactionCreationException('Unable start new transaction when old not processed');
        }

        return [
            'amount' => array_get($transaction, 'plan.price'),
            'idempotent_key' => array_get($transaction, 'token')
        ];
    }
}

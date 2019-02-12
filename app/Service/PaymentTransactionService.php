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

    public function __construct()
    {
        $this->repository = app(PaymentTransactionRepository::class);
        $this->planRepository = app(PlanRepository::class);
    }

    public function begin(int $userId, int $planId): array
    {
        $transaction = $this->repository->findBy(['user_id' => $userId], ['plan']);

        if (
            array_has($transaction, 'plan.id') &&
            array_get($transaction, 'plan.id') !== $planId
        ) {
            throw new UnableTransactionCreationException('Unable start new transaction when old not processed');
        }

        if ([] === $transaction) {
            $plan = $this->planRepository->find($planId);

            $transaction = $this->repository->create([
                'user_id' => $userId,
                'plan_id' => $plan['id'],
                'token' => Uuid::uuid4()
            ]);

            return [
                'amount' => $plan['price'],
                'return_url_slug' => $plan['slug'],
                'idempotent_key' => $transaction['token']
            ];
        }

        return [
            'amount' => array_get($transaction, 'plan.price'),
            'return_url_slug' => array_get($transaction, 'plan.slug'),
            'idempotent_key' => array_get($transaction, 'token')
        ];
    }

    public function finish(int $userId): void
    {
        $this->repository->delete(['user_id' => $userId]);
    }

    public function rollback(string $token)
    {
        $this->repository->delete(['token' => $token]);
    }
}

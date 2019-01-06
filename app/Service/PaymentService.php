<?php

namespace App\Service;

use App\Exceptions\UnableTransactionCreationException;
use App\Repository\PaymentRepository;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use App\Support\Interfaces\PaymentClientInterface;
use App\Util\YandexPaymentClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @property PaymentRepository $repository
 * @property UserRepository $userRepository
 * @property PlanRepository $planRepository
 * @property PaymentClientInterface $paymentClient
 * @property PaymentTransactionService $paymentTransactionService
 * */
class PaymentService extends Service
{
    protected $paymentClient;
    protected $paymentTransactionService;
    protected $planRepository;
    protected $userRepository;

    public function __construct(PaymentClientInterface $paymentClient)
    {
        $this->setRepository(PaymentRepository::class);

        $this->userRepository = app(UserRepository::class);
        $this->planRepository = app(PlanRepository::class);
        $this->paymentClient = $paymentClient;
        $this->paymentTransactionService = app(PaymentTransactionService::class);
    }

    public function create(int $userId, int $planId)
    {
        try {
            $paymentData = $this->paymentTransactionService->begin($userId, $planId);

            $payment = $this->paymentClient->create($paymentData);

            $this->repository->create(
                array_merge($payment['entity'], ['user_id' => $userId, 'plan_id' => $planId])
            );

            return array_only($payment, 'url');
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function handle($paymentData): void
    {
        try {
            $payment = $this->paymentClient->get(array_get($paymentData, 'object.id'));

            if ($payment['status'] === YandexPaymentClient::PAYMENT_STATUS_SUCCESS) {
                $this->finishPayment($payment);
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    protected function finishPayment(array $payment): void
    {
        $where = ['payment_id' => $payment['payment_id']];

        $updated = $this->repository->updateBy($where, $payment);

        $this->updateUserPlan($updated['user_id'], $updated['plan_id']);
        $this->paymentTransactionService->finish($updated['user_id']);
    }

    protected function updateUserPlan(int $userId, int $planId): void
    {
        $plan = $this->planRepository->find($planId);
        $this->userRepository->update($userId, ['points' => $plan['points']], true);
    }
}
<?php

namespace App\Service;

use App\Exceptions\UnableTransactionCreationException;
use App\Model\Plan;
use App\Repository\PaymentRepository;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use App\Support\Interfaces\PaymentClientInterface;
use App\Util\YandexPaymentClient;
use Carbon\Carbon;
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

            if ($payment['status'] === YandexPaymentClient::PAYMENT_STATUS_CANCEL) {
                $this->closePayment($payment);
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function search(?array $filters = []): array
    {
        $filters['with'] = ['user', 'plan'];
        return $this->repository->search($filters);
    }

    protected function finishPayment(array $payment): void
    {
        $closedPayment = $this->closePayment($payment);

        $this->updateUserPlan($closedPayment['user_id'], $closedPayment['plan_id']);
    }

    protected function closePayment(array $payment): array
    {
        $where = ['payment_id' => $payment['payment_id']];

        $updated = $this->repository->updateBy($where, $payment);

        $this->paymentTransactionService->finish($updated['user_id']);

        return $updated;
    }

    protected function updateUserPlan(int $userId, int $planId): void
    {
        $plan = $this->planRepository->find($planId);
        $user = $this->userRepository->find($userId);

        if (Plan::SUB_TYPE === $plan['type']) {
            $subscribeBefore = Carbon::now()->addDays(config('defaults.subscription.ttl'))->toDateString();
            $data = [
                'plan_id' => $plan['id'],
                'subscribed_before' => $subscribeBefore,
                'points' => 0
            ];
        } else {
            $data = [
                'points' => $plan['points']
            ];
        }

        $this->userRepository->update($userId, $data, true);
    }
}

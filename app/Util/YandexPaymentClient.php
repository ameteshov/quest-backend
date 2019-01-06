<?php

namespace App\Util;

use App\Support\Interfaces\PaymentClientInterface;
use Carbon\Carbon;
use YandexCheckout\Client;
use YandexCheckout\Model\PaymentInterface;
use YandexCheckout\Request\Payments\CreatePaymentResponse;

/**
 * @property Client $apiClient
 * @package App\Util
 */
class YandexPaymentClient implements PaymentClientInterface
{
    public const PAYMENT_STATUS_SUCCESS = 'succeeded';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_WAITING_CAPTURE = 'waiting_for_capture';
    public const PAYMENT_STATUS_CANCEL = 'canceled';

    protected $apiClient;

    public function __construct()
    {
        $this->apiClient = app(Client::class);

        $config = config('defaults.checkout');

        $this->apiClient->setAuth($config['merchant_key'], $config['merchant_token']);
    }

    public function create(array $data): array
    {
        $response = $this->apiClient->createPayment(
            $this->buildCreateRequestData($data),
            $data['idempotent_key']
        );

        return $this->transformApiResponse($response);
    }

    public function refund(): array
    {
        // TODO: Implement refund() method.
    }

    public function get(string $uuid): array
    {
        $payment = $this->apiClient->getPaymentInfo($uuid);

        return $this->transformPaymentObject($payment);
    }

    protected function buildCreateRequestData(array $paymentData): array
    {
        return [
            'amount' => [
                'value' => array_get($paymentData, 'amount'),
                'currency' => array_get($paymentData, 'currency', config('defaults.checkout.default_currency'))
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => config('defaults.checkout.return_url')
            ],
            'description' => array_get($paymentData, 'description'),
            'capture' => true
        ];
    }

    protected function transformApiResponse(CreatePaymentResponse $response): array
    {
        return [
            'url' => $response->getConfirmation()->getConfirmationUrl(),
            'entity' => $this->transformPaymentObject($response),
        ];
    }

    protected function transformPaymentObject(PaymentInterface $payment): array
    {
        return [
            'payment_id' => $payment->getId(),
            'status' => $payment->getStatus(),
            'is_paid' => $payment->getPaid(),
            'amount' => $payment->getAmount()->getValue(),
            'currency' => $payment->getAmount()->getCurrency(),
            'started_at' => $payment->getCreatedAt()->format('Y-m-d H:i:s'),
            'description' => $payment->getDescription()
        ];
    }
}

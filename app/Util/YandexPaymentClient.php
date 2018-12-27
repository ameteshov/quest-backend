<?php

namespace App\Util;

use App\Support\Interfaces\PaymentClientInterface;
use YandexCheckout\Client;

class YandexPaymentClient implements PaymentClientInterface
{
    protected $apiClient;

    public function __construct()
    {
        $this->apiClient = new Client();

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

    public function get(): array
    {
        // TODO: Implement get() method.
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
                'return_url' => config('defaults.checkout.return_url'),
                'locale' => array_get($paymentData, 'locale', config('defaults.checkout.default_locale'))
            ],
            'description' => array_get($paymentData, 'description')
        ];
    }

    protected function transformApiResponse(array $response): array
    {
        return [
            'url' => array_get($response, 'confirmation.confirmation_url'),
            'entity' => [
                'payment_id' => '',
                'status' => '',
                'is_paid' => '',
                'amount' => '',
                'currency' => '',
                'started_at' => '',
                'description' => ''
            ],
        ];
    }
}

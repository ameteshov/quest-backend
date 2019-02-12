<?php

namespace App\Util;

use App\Support\Interfaces\PaymentClientInterface;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Self_;
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

    public const BANK_CARD_PAYMENT_METHOD = 'bank_card';
    public const YANDEX_MONEY_PAYMENT_METHOD = 'yandex_money';
    public const SBERBANK_PAYMENT_METHOD = 'sberbank';
    public const B2B_SBERBANK_PAYMENT_METHOD = 'b2b_sberbank';
    public const QIWI_PAYMENT_METHOD = 'qiwi';
    public const WEBMONEY_PAYMENT_METHOD = 'webmoney';

    public const PAYMENT_METHODS = [
        self::BANK_CARD_PAYMENT_METHOD,
        self::YANDEX_MONEY_PAYMENT_METHOD,
        self::SBERBANK_PAYMENT_METHOD,
        self::B2B_SBERBANK_PAYMENT_METHOD,
        self::QIWI_PAYMENT_METHOD,
        self::WEBMONEY_PAYMENT_METHOD
    ];

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
                'return_url' => config('defaults.checkout.return_url') . array_get($paymentData, 'return_url_slug')
            ],
            'payment_method_data' => $this->getPaymentMethodData($paymentData),
            'description' => array_get($paymentData, 'description'),
            'capture' => true
        ];
    }

    protected function getPaymentMethodData(array $paymentData): array
    {
        $result = [];
        $result['type'] = array_get($paymentData, 'method', null);

        if (YandexPaymentClient::B2B_SBERBANK_PAYMENT_METHOD === $result['type']) {
            $result['payment_purpose'] = 'Оплата в системе hr-tophunter.ru';
            $result['vat_data'] = [
                'type' => 'calculated',
                'rate' => 0,
                'amount' => [
                    'currency' => 'RUB',
                    'value' => '0.00'
                ]
            ];
        }
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

<?php

namespace App\Console\Commands;

use App\Service\PaymentService;
use App\Service\PaymentTransactionService;
use App\Support\Interfaces\PaymentClientInterface;
use App\Util\YandexPaymentClient;
use Illuminate\Console\Command;

/**
 * Class ClosePaymentTransactions
 * @package App\Console\Commands
 * @property PaymentClientInterface paymentClient
 * @property PaymentTransactionService transactionService
 * @property PaymentService paymentService
 */
class ClosePaymentTransactions extends Command
{
    protected $signature = 'payments:close';

    protected $description = 'Command close payments in cancelled status';

    protected $paymentClient;
    protected $paymentService;
    protected $transactionService;

    public function __construct()
    {
        parent::__construct();

        $this->paymentClient = app(PaymentClientInterface::class);
        $this->paymentService = app(PaymentService::class);
        $this->transactionService = app(PaymentTransactionService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        logger('Start closing non-pending transactions');

        $transactions = $this->transactionService->get();

        if ([] !== $transactions) {
            foreach ($transactions as $transaction) {
                $payment = $this->paymentClient->get($transaction['token']);

                if (YandexPaymentClient::PAYMENT_STATUS_PENDING !== $payment['status']) {
                    $this->paymentService->update($transaction['payment_id'], [
                        'status' => $payment['status'],
                        'is_paid' => (YandexPaymentClient::PAYMENT_STATUS_SUCCESS === $payment['status']) ? true : false
                    ]);

                    $this->transactionService->delete($transaction['id']);

                    logger("Transaction {$transaction['token']} was closed - payment status: {$payment['status']}");
                }
            }
        }

        logger('Finish closing non-pending transactions');
    }
}

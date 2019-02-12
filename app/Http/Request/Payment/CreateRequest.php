<?php

namespace App\Request\Payment;

use App\Model\Role;
use App\Request\Request;
use App\Util\YandexPaymentClient;

class CreateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_id' => 'required|integer|exists:plans,id',
            'method' => 'required|string|in:' . implode(',', YandexPaymentClient::PAYMENT_METHODS)
        ];
    }
}

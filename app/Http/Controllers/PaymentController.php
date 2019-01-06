<?php

namespace App\Http\Controllers;

use App\Request\Payment\CreateRequest;
use App\Request\Payment\HandleRequest;
use App\Service\PaymentService;

class PaymentController extends Controller
{
    public function create(CreateRequest $request, PaymentService $service)
    {
        $result = $service->create($request->user()->id, $request->input('plan_id'));

        return response()->json($result);
    }

    public function get()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function handle(HandleRequest $request, PaymentService $service)
    {
        $service->handle($request->all());

        return response()->json('');
    }
}

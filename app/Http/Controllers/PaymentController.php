<?php

namespace App\Http\Controllers;

use App\Request\Payment\CreateRequest;
use App\Request\Payment\HandleRequest;
use App\Request\Payment\SearchRequest;
use App\Service\PaymentService;

class PaymentController extends Controller
{
    public function create(CreateRequest $request, PaymentService $service)
    {
        $result = $service->create($request->user()->id, $request->input('plan_id'));

        return response()->json($result);
    }

    public function search(SearchRequest $request, PaymentService $service)
    {
        $result = $service->search($request->all());

        return response()->json($result);
    }

    public function handle(HandleRequest $request, PaymentService $service)
    {
        $service->handle($request->all());

        return response()->json('');
    }
}

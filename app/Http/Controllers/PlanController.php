<?php

namespace App\Http\Controllers;

use App\Request\Plan\CreateRequest;
use App\Request\Plan\DeleteRequest;
use App\Request\Plan\GetRequest;
use App\Request\Plan\SearchRequest;
use App\Request\Plan\UpdateRequest;
use App\Service\PlanService;
use Illuminate\Http\Response;

class PlanController extends Controller
{
    public function create(CreateRequest $request, PlanService $service)
    {
        $result = $service->create($request->all());

        return response()->json($result);
    }

    public function get(GetRequest $request, PlanService $service, int $id)
    {
        $result = $service->find($id);

        return response()->json($result);
    }

    public function update(UpdateRequest $request, PlanService $service, int $id)
    {
        $service->update($id, $request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteRequest $request, PlanService $service, int $id)
    {
        $service->delete($id);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchRequest $request, PlanService $service)
    {
        $result = $service->search(
            $request->user()->toArray(),
            $request->all()
        );

        return response()->json($result);
    }
}

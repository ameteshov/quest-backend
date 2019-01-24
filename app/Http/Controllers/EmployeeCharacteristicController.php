<?php

namespace App\Http\Controllers;

use App\Request\EmployeeCharacteristic\CreateRequest;
use App\Request\EmployeeCharacteristic\DeleteRequest;
use App\Request\EmployeeCharacteristic\GetRequest;
use App\Request\EmployeeCharacteristic\SearchRequest;
use App\Request\EmployeeCharacteristic\UpdateRequest;
use App\Service\EmployeeCharacteristicService;
use Illuminate\Http\Response;

class EmployeeCharacteristicController extends Controller
{
    public function create(CreateRequest $request, EmployeeCharacteristicService $service)
    {
        $result = $service->create($request->all());

        return response()->json($result);
    }

    public function get(GetRequest $request, EmployeeCharacteristicService $service, int $id)
    {
        $result = $service->find($id);

        return response()->json($result);
    }

    public function update(UpdateRequest $request, EmployeeCharacteristicService $service, int $id)
    {
        $service->update($id, $request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteRequest $request, EmployeeCharacteristicService $service, int $id)
    {
        $service->delete($id);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchRequest $request, EmployeeCharacteristicService $service)
    {
        $result = $service->search(
            $request->user()->toArray(),
            $request->all()
        );

        return response()->json($result);
    }
}

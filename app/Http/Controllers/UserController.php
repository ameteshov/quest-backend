<?php

namespace App\Http\Controllers;

use App\Request\User\DeleteRequest;
use App\Request\User\GetRequest;
use App\Request\User\SearchRequest;
use App\Request\User\UpdateRequest;
use App\Request\Users\CreateRequest;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function create(CreateRequest $request, UserService $service): JsonResponse
    {
        $user = $service->createFromPanel($request->all());

        return response()->json($user);
    }

    public function get(GetRequest $request, UserService $service, int $id): JsonResponse
    {
        $user = $service->find($id);

        return response()->json($user);
    }

    public function search(SearchRequest $request, UserService $service): JsonResponse
    {
        $result = $service->search($request->user()->id, $request->all());

        return response()->json($result);
    }

    public function update(UpdateRequest $request, UserService $service, int $id): JsonResponse
    {
        $service->update($id, $request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteRequest $request, UserService $service, int $id): JsonResponse
    {
        $service->delete($id);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }
}

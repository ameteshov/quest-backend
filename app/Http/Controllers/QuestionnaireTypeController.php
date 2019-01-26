<?php

namespace App\Http\Controllers;

use App\Request\QuestionnaireType\CreateRequest;
use App\Request\QuestionnaireType\DeleteRequest;
use App\Request\QuestionnaireType\GetRequest;
use App\Request\QuestionnaireType\SearchRequest;
use App\Request\QuestionnaireType\UpdateRequest;
use App\Service\QuestionnaireTypeService;
use Illuminate\Http\Response;

class QuestionnaireTypeController extends Controller
{
    public function create(CreateRequest $request, QuestionnaireTypeService $service)
    {
        $entity = $service->create($request->all());

        return response()->json($entity);
    }

    public function get(GetRequest $request, QuestionnaireTypeService $service, $id)
    {
        $entity = $service->find($id);

        return response()->json($entity);
    }

    public function update(UpdateRequest $request, QuestionnaireTypeService $service, $id)
    {
        $service->update($id, $request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteRequest $request, QuestionnaireTypeService $service, $id)
    {
        $service->delete($id);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchRequest $request, QuestionnaireTypeService $service)
    {
        $result = $service->search($request->all());

        return response()->json($result);
    }
}

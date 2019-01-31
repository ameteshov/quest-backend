<?php

namespace App\Http\Controllers;

use App\Request\Questionnaire\{CreateRequest,
    DeleteRequest,
    GetByHashRequest,
    GetRequest,
    SearchRequest,
    SendRequest,
    SubmitRequest,
    UpdateRequest};
use App\Service\QuestionnaireResultService;
use App\Service\QuestionnaireService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionnaireController extends Controller
{
    public function create(CreateRequest $request, QuestionnaireService $service)
    {
        $entity = $service->create($request->user()->id, $request->all());

        return response()->json($entity);
    }

    public function get(GetRequest $request, QuestionnaireService $service, $id)
    {
        $entity = $service->find(
            $id,
            $request->user()->toArray(),
            $request->input('with', [])
        );

        return response()->json($entity);
    }

    public function update(UpdateRequest $request, QuestionnaireService $service, $id)
    {
        $service->update($id, $request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteRequest $request, QuestionnaireService $service, $id)
    {
        $service->delete($id);

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchRequest $request, QuestionnaireService $service)
    {
        $result = $service->search($request->all());

        return response()->json($result);
    }

    public function send(SendRequest $request, QuestionnaireService $service, $id)
    {
        $service->send($id, $request->user()->id, $request->input('list'));

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function submit(SubmitRequest $request, QuestionnaireResultService $service)
    {
        $service->submit($request->all());

        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function getByHash(GetByHashRequest $request, QuestionnaireService $service, $hash)
    {
        $questionnaire = $service->findByHash($hash);

        return response()->json($questionnaire);
    }

    public function getStatistic(Request $request, QuestionnaireService $service)
    {
        $result = $service->getStatistic(
            $request->user()->id,
            $request->input('vacancies', [])
        );

        return response()->json($result);
    }
}

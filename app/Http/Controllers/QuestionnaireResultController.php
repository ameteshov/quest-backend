<?php

namespace App\Http\Controllers;

use App\Request\QuestionnaireResult\GetCandidateRequest;
use App\Request\QuestionnaireResult\GetRequest;
use App\Request\QuestionnaireResult\GetVacanciesRequest;
use App\Service\QuestionnaireResultService;

class QuestionnaireResultController extends Controller
{
    public function getVacancies(GetVacanciesRequest $request, QuestionnaireResultService $service)
    {
        $result = $service->getVacancies($request->user()->id);

        return response()->json($result);
    }

    public function getCandidate(GetCandidateRequest $request, QuestionnaireResultService $service)
    {
        $result = $service->getCandidate($request->user()->id, $request->input('email'));

        return response()->json($result);
    }

    public function get(GetRequest $request, QuestionnaireResultService $service, $id)
    {
        $result = $service->find($id, ['questionnaire']);

        return response()->json($result);
    }
}

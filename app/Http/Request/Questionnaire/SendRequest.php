<?php

namespace App\Request\Questionnaire;

use App\Request\Request;
use App\Service\QuestionnaireResultService;
use App\Service\QuestionnaireService;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'list' => 'required|array',
            'list.*.email' => 'string|required|email',
            'list.*.name' => 'string|required',
            'list.*.vacancy' => 'string|required'
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(QuestionnaireService::class);
        $userService = app(UserService::class);
        $questionnaire = [
            'id' => $this->route('id'),
            'is_active' => true
        ];

        if (!$service->exists($questionnaire)) {
            throw new NotFoundHttpException('Questionnaire not found');
        }

        if ($userService->isLimitExceeded($this->user()->id, $this->getListCount())) {
            throw new BadRequestHttpException('You can not sent questionnaires anymore, please upgrade plan');
        }

        $this->validateVacancies();
    }

    protected function getListCount()
    {
        return count($this->input('list'));
    }

    protected function validateVacancies()
    {
        $service = app(QuestionnaireResultService::class);
        $response = [];

        foreach ($this->input('list') as $key => $item) {
            $result = $this->validateVacancy($service, $item);
            if (null !== $result) {
                $response[$key] = $result;
            }
        }

        if ([] !== $response) {
            throw new BadRequestHttpException(json_encode($response));
        }
    }

    protected function validateVacancy(QuestionnaireResultService $service, array $item)
    {
        $result = $service->findBy([
            'user_id' => $this->user()->id,
            'email' => $item['email']
        ]);

        if (!empty($result) && $result['vacancy'] !== strtolower($item['vacancy'])) {
            return [
                'email' => $item['email'],
                'vacancy' => $result['vacancy']
            ];
        }

        return null;
    }
}

<?php

namespace App\Request\QuestionnaireType;

use App\Model\Role;
use App\Request\Request;
use App\Service\QuestionnaireTypeService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return $this->user()->role_id === Role::ROLE_ADMIN;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(QuestionnaireTypeService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('Questionnaire Type does not exist');
        }

        if ($service->hasRelatedQuestionnaires($this->route('id'))) {
            throw new BadRequestHttpException('Questionnaire type with related questionnaires can not be deleted');
        }
    }
}

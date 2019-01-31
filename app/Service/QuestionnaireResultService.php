<?php

namespace App\Service;

use App\Model\Questionnaire;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionnaireResultRepository;

/**
 * @property QuestionnaireResultRepository $repository
 * @property QuestionnaireRepository $questionnaireRepository
 * */
class QuestionnaireResultService extends Service
{
    protected $questionnaireRepository;

    public function __construct()
    {
        $this->setRepository(QuestionnaireResultRepository::class);
        $this->questionnaireRepository = app(QuestionnaireRepository::class);
    }

    public function submit(array $data)
    {
        $data['score'] = $this->getQuestionnaireScore($data);

        $this->repository->saveSubmitted($data['hash'], $data);
    }

    protected function getQuestionnaireScore($data)
    {
        $questionnaire = $this->questionnaireRepository->findByHash($data['hash']);

        if (null !== array_get($questionnaire, 'type_id')) {
            return $this->getSummaryScore($data['content']);
        }

        return null;
    }

    protected function getSummaryScore(array $answers)
    {
        return array_sum(array_map(function ($item) {
            return $item['result'];
        }, $answers));
    }
}

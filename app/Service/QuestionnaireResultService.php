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

        if (Questionnaire::AVG_TYPE === $questionnaire['result_type']) {
            return $this->getAverageScore($data['content']);
        }

        if (Questionnaire::SUM_TYPE === $questionnaire['result_type']) {
            return $this->getSummaryScore($data['content']);
        }

        return null;
    }

    protected function getAverageScore(array $answers)
    {
        $sum = $this->getSummaryScore($answers);

        return round($sum / count($answers));
    }

    protected function getSummaryScore(array $answers)
    {
        return array_sum(array_map(function ($item) {
            return $item['result'];
        }, $answers));
    }
}

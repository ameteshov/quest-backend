<?php

namespace App\Repository;

use App\Model\QuestionnaireType;

class QuestionnaireTypeRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(QuestionnaireType::class);
    }

    public function search(array $filters): array
    {
        return $this->getSearchQuery($filters)
            ->getResult();
    }

    public function hasRelatedQuestionnaires(int $id): bool
    {
        return $this->getQuery()
            ->where('id', $id)
            ->whereHas('questionnaire')
            ->exists();
    }
}

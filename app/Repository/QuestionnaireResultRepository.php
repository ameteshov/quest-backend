<?php

namespace App\Repository;

use App\Model\QuestionnaireResult;

class QuestionnaireResultRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(QuestionnaireResult::class);
    }

    public function isAvailable(string $hash)
    {
        $where = [
            'access_hash' => $hash,
            'is_passed' => false
        ];

        return $this->getQuery()
            ->where($where)
            ->exists();
    }
}

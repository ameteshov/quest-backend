<?php

namespace App\Service;

use App\Repository\QuestionnaireResultRepository;

/**
 * @property QuestionnaireResultRepository $repository
 * */
class QuestionnaireResultService extends Service
{
    public function __construct()
    {
        $this->setRepository(QuestionnaireResultRepository::class);
    }

    public function submit(array $data)
    {
        $where = ['access_hash' => $data['hash']];

        $data = [
            'content' => $data['content'],
            'is_passed' => true
        ];

        $this->repository->updateBy($where, $data);
    }
}

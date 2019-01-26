<?php

namespace App\Service;

use App\Repository\QuestionnaireTypeRepository;

/**
 * @property QuestionnaireTypeRepository $repository
 * */
class QuestionnaireTypeService extends Service
{
    public function __construct()
    {
        $this->setRepository(QuestionnaireTypeRepository::class);
    }
}

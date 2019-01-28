<?php

namespace App\Repository;

use App\Model\QuestionnaireResult;
use Carbon\Carbon;

class QuestionnaireResultRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(QuestionnaireResult::class);
    }

    public function saveSubmitted(string $hash, array $submittedData): void
    {
        $updatedData = [
            'is_passed' => true,
            'content' => $submittedData['content'],
            'recipient_name' => array_get($submittedData, 'info.name'),
            'recipient_phone' => array_get($submittedData, 'info.phone'),
            'birthday_date' => Carbon::parse(array_get($submittedData, 'info.birthday'))->toDateString(),
            'score' => array_get($submittedData, 'score')
        ];

        if (null !== array_get($submittedData, 'info.email', null)) {
            $updatedData['email'] = array_get($submittedData, 'info.email');
        }

        $this->updateBy(['access_hash' => $hash], $updatedData);
    }
}

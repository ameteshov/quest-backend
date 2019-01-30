<?php

namespace App\Repository;

use App\Model\QuestionnaireResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function getStatisticForUser(int $userId)
    {
        $result = $this->getQuery()
            ->addSelect(DB::raw('sum(score) as score_sum'))
            ->where('user_id', $userId)
            ->whereHas('questionnaire', function ($query) {
                $query->whereNotNull('type_id');
            })
            ->groupBy('email')
            ->orderBy('score', 'desc')
            ->orderBy('vacancy', 'asc')
            ->with('questionnaire')
            ->get()
            ->toArray();

        return $result ?? [];
    }
}

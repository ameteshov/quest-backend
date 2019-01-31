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

    public function getStatisticForUser(int $userId, array $vacancies = [])
    {
        $query = $this->getQuery()
            ->addSelect(DB::raw('sum(score) as score_sum'))
            ->addSelect(DB::raw('sum(questionnaires.max_score) as score_max'))
            ->addSelect(['email', 'recipient_name', 'vacancy'])
            ->where('questionnaires_results.user_id', $userId)
            ->whereHas('questionnaire', function ($query) {
                $query->whereNotNull('questionnaires.type_id');
            })
            ->join('questionnaires', 'questionnaires_results.questionnaire_id', '=', 'questionnaires.id')
            ->groupBy('email')
            ->groupBy('vacancy')
            ->groupBy('recipient_name')
            ->orderBy('vacancy', 'asc')
            ->orderBy('score_sum', 'desc');

        if ([] !== $vacancies) {
            $query->whereIn('vacancy', $vacancies);
        }

        $result = $query->get()->toArray();

        return $result ?? [];
    }
}

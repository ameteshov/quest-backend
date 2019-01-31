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
        // TODO:: добавить валидацию чтобы запрещать людям слать анкеты на разные вакансии или переработать этот функционал к хуям
        $query = $this->getQuery()
            ->addSelect(DB::raw('sum(score) as score_sum'))
            ->addSelect(DB::raw('sum(questionnaires.success_score) as score_max'))
            //->addSelect(DB::raw("GROUP_CONCAT(vacancy SEPARATOR ',') as vacancy_join"))
            ->addSelect(['email', 'recipient_name', 'vacancy'])
            ->where('questionnaires_results.user_id', $userId)
            ->whereHas('questionnaire', function ($query) {
                $query->whereNotNull('questionnaires.type_id');
            })
            ->join('questionnaires', 'questionnaires_results.questionnaire_id', '=', 'questionnaires.id')
            ->groupBy('email')
            ->groupBy('recipient_name')
            ->groupBy('vacancy')
            ->orderBy('score_sum', 'desc')
            //->orderBy('vacancy_join', 'asc');
            ->orderBy('vacancy', 'asc');

        if ([] !== $vacancies) {
            $query->havingRaw(DB::raw("vacancy_join like '%?%'", [implode(',', $vacancies)]));
        }

        $result = $query->get()->toArray();

        return $result ?? [];
    }
}

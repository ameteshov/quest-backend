<?php

namespace App\Service;

use App\Jobs\SendFormEmailJob;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionnaireResultRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\DB;

/**
 * @property QuestionnaireRepository $repository
 * @property UserRepository $userRepository
 * @property QuestionnaireResultRepository $resultsRepository
 * */
class QuestionnaireService extends Service
{
    protected $userRepository;
    protected $resultsRepository;

    public function __construct()
    {
        $this->setRepository(QuestionnaireRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->resultsRepository = app(QuestionnaireResultRepository::class);
    }

    public function send(int $id, int $senderId, array $sendList)
    {
        foreach ($sendList as $item) {
            $this->sendToRecipient($id, $senderId, $item);
        }
    }

    public function isLimitExceeded(int $userId, ?int $sendCount = 0)
    {
        $limit = $this->userRepository->getLimit($userId);
        $used = $this->resultsRepository->count(['user_id' => $userId]);

        return ($used + $sendCount) > $limit;
    }

    public function search(?array $filters = [])
    {
        $filters['with'] = ['results'];

        return $this->repository->search($filters);
    }

    protected function sendToRecipient($id, $senderId, array $recipientData)
    {
        $accessHash = md5(uniqid('', true));

        $this->repository->addRecipient($id, [
            'user_id' => $senderId,
            'email' => $recipientData['email'],
            'recipient_name' => $recipientData['name'],
            'access_hash' => $accessHash
        ]);

        dispatch(
            new SendFormEmailJob($recipientData['email'], [
                'name' => $recipientData['name']
            ])
        );
    }
}

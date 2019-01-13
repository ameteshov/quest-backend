<?php

namespace App\Service;

use App\Jobs\SendFormEmailJob;
use App\Model\Role;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionnaireResultRepository;
use App\Repository\UserRepository;

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

    public function find(int $id, array $user, ?array $with = [])
    {
        if (Role::ROLE_USER === $user['role_id']) {
            $with =
                in_array('results', $with, true) ?
                $this->getFilteredResultRelation($user['id'], $with) :
                $with;
        }

        return $this->repository->find($id, $with);
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

        return $sendCount > $limit;
    }

    public function search(?array $filters = [])
    {
        $filters['with'] = ['results'];

        return $this->repository->search($filters);
    }

    public function existsAndAvailable(int $id, int $roleId)
    {
        $where = ['id' => $id];

        if (Role::ROLE_USER === $roleId) {
            $where['is_active'] = 1;
        }

        return $this->repository->exists($where);
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

        $this->userRepository->decrementAvailableSurveys($senderId);

        dispatch(
            new SendFormEmailJob($recipientData['email'], [
                'name' => $recipientData['name'],
                'hash' => $accessHash
            ])
        );
    }

    protected function getFilteredResultRelation(int $userId, array $with)
    {
        $with['results'] = function($query) use ($userId) {
                $query->where('user_id', $userId);
        };

        return $with;
    }
}

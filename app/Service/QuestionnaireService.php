<?php

namespace App\Service;

use App\Jobs\SendFormEmailJob;
use App\Model\Role;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionnaireResultRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;

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

    public function create(int $userId, int $roleId, array $data)
    {
        if (Role::ROLE_ADMIN !== $roleId) {
            $data['user_id'] = $userId;
        }

        $data['max_score'] = $this->calculateMaxScore($data['content']);

        return $this->repository->create($data);
    }

    public function find(int $id, array $user, ?array $with = [])
    {
        if (Role::ROLE_USER === $user['role_id']) {
            $with = in_array('results', $with, true) ?
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

    public function getStatistic(int $userId, $vacancies = [])
    {
        return $this->resultsRepository->getStatisticForUser($userId, $vacancies);
    }

    protected function sendToRecipient($id, $senderId, array $recipientData)
    {
        $userService = app(UserService::class);

        $accessHash = md5(uniqid('', true));

        $result = $this->resultsRepository->findBy([
            'user_id' => $senderId,
            'email' => $recipientData['email'],
            'questionnaire_id' => $id
        ]);

        if (!empty($result)) {
            $this->resultsRepository->update($result['id'], [
                'access_hash' => $accessHash,
                'expired_at' => Carbon::now()->addHour(config('defaults.forms.ttl'))->toDateTimeString(),
                'content' => null,
                'recipient_name' => $recipientData['name'],
                'vacancy' => strtolower($recipientData['vacancy'])
            ]);
        } else {
            $this->repository->addRecipient($id, [
                'user_id' => $senderId,
                'email' => $recipientData['email'],
                'recipient_name' => $recipientData['name'],
                'vacancy' => strtolower($recipientData['vacancy']),
                'access_hash' => $accessHash,
                'expired_at' => Carbon::now()->addHour(config('defaults.forms.ttl'))->toDateTimeString()
            ]);
        }

        if (!$userService->hasActiveSubscription($senderId)) {
            $this->userRepository->updateSendCount($senderId);
        }

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

    protected function calculateMaxScore(array $data)
    {
        if (!array_has($data, 'answers')) {
            return null;
        }
        
        $maxAnswer = max(
            array_map(
                function ($answer) {
                    return $answer['points'];
                },
                $data['answers']
            )
        );

        return (int)count($data['questions']) * (int)$maxAnswer;
    }
}

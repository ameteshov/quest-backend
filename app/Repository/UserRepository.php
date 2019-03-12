<?php

namespace App\Repository;

use App\Model\User;
use App\Support\Interfaces\ModelRepositoryInterface;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(User::class);
    }

    public function getLimit(int $userId)
    {
        $user = $this->find($userId);

        return (int)array_get($user, 'questionnaires_count');
    }

    public function search(?array $filters = [])
    {
        return $this->getSearchQuery($filters)
            ->filterByCurrentUser()
            ->with()
            ->getResult();
    }

    public function updateSendCount(int $userId)
    {
        $query = $this->getQuery()
            ->where('id', $userId);

        $query->increment('questionnaires_count');
        $query->decrement('points');
    }

    public function findBySocialId($socialId)
    {
        return $this->getQuery()
            ->where(function($query) use ($socialId) {
                $query->orWhere('google_id', $socialId)
                    ->orWhere('vk_id', $socialId)
                    ->orWhere('facebook_id', $socialId)
                    ->orWhere('twitter_id', $socialId)
                    ->orWhere('odnoklassniki_id', $socialId);
            })
            ->first();
    }

    public function getSubscribed()
    {
        $users = $this->getQuery()
            ->whereNotNull('subscribed_before')
            ->get();

        return empty($users) ? [] : $users->toArray();
    }

    protected function filterByCurrentUser(): ModelRepositoryInterface
    {
        if (array_has($this->filters, 'current_user_id')) {
            $this->searchQuery->where('id', '<>', $this->filters['current_user_id']);
        }

        return $this;
    }
}

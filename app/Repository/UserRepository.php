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

    protected function filterByCurrentUser(): ModelRepositoryInterface
    {
        if (array_has($this->filters, 'current_user_id')) {
            $this->searchQuery->where('id', '<>', $this->filters['current_user_id']);
        }

        return $this;
    }
}

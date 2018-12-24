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
        $user = $this->find($userId, ['plan']);

        return (int)array_get($user, 'plan.points', config('defaults.free_plan.points'));
    }

    public function search(?array $filters = [])
    {
        return $this->getSearchQuery($filters)
            ->filterByCurrentUser()
            ->with()
            ->getResult();
    }

    protected function filterByCurrentUser(): ModelRepositoryInterface
    {
        if (array_has($this->filters, 'current_user_id')) {
            $this->searchQuery->where('id', '<>', $this->filters['current_user_id']);
        }

        return $this;
    }
}

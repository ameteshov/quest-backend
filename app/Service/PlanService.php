<?php

namespace App\Service;

use App\Model\Role;
use App\Repository\PlanRepository;

/**
 * @property PlanRepository $repository
 * */
class PlanService extends Service
{
    public function __construct()
    {
        $this->setRepository(PlanRepository::class);
    }

    public function exists(int $id, array $user): bool
    {
        if ($user['role_id'] === Role::ROLE_USER) {
            return $this->repository->exists(['id' => $id, 'is_active' => 1]);
        }

        return $this->repository->exists(['id' => $id]);
    }

    public function search(array $user, ?array $filters = []): array
    {
        if ($user['role_id'] === Role::ROLE_USER) {
            $filters['is_active'] = 1;
        }

        return $this->repository->search($filters);
    }
}

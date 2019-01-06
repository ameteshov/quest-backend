<?php

namespace App\Service;

use App\Jobs\SendRegistrationEmailJob;
use App\Jobs\SendResetPasswordEmailJob;
use App\Model\Role;
use App\Repository\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * @property UserRepository $repository
 * @property Hasher $hasher
 * */
class UserService extends Service
{
    protected $hasher;

    public function __construct()
    {
        $this->setRepository(UserRepository::class);

        $this->hasher = app(Hasher::class);
    }

    public function create(array $userData): array
    {
        $userData['password'] = $this->hasher->make($userData['password']);
        $userData['role_id'] = Role::DEFAULT_ROLE;
        $userData['questionnaires_count'] = config('defaults.free_plan.points');

        $user = $this->repository->create($userData);

        dispatch(new SendRegistrationEmailJob($user['email'], ['data' => $user]));

        return $user;
    }

    public function createFromPanel(array $userData): array
    {
        $userData['role_id'] = $userData['role_id'] ?? Role::DEFAULT_ROLE;
        $userData['reset_token'] = uniqid('', true);

        $user = $this->repository->create($userData);

        dispatch(new SendResetPasswordEmailJob($user['email'], ['data' => $user]));

        return $user;
    }

    public function update(int $id, array $data, int $role): void
    {
        $useForce = $role === Role::ROLE_ADMIN;

        $this->repository->update($id, $data, $useForce);
    }

    public function resetPassword(string $email): void
    {
        $resetHash = uniqid('', true);

        $user = $this->repository->findBy(['email' => $email]);
        $user = $this->repository->update($user['id'], ['reset_token' => $resetHash]);

        dispatch(new SendResetPasswordEmailJob($user['email'], ['data' => $user]));
    }

    public function confirmPassword(array $userData): void
    {
        $data = [
            'password' => $this->hasher->make($userData['password']),
            'reset_token' => null
        ];

        $this->repository->updateBy(['reset_token' => $userData['hash']], $data);
    }

    public function search(int $currentUserId, ?array $filters = []): array
    {
        $filters['current_user_id'] = $currentUserId;
        $filters['with'] = ['plan'];

        return $this->repository->search($filters);
    }
}

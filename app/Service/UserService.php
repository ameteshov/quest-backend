<?php

namespace App\Service;

use App\Jobs\SendRegistrationEmailJob;
use App\Jobs\SendResetPasswordEmailJob;
use App\Model\Role;
use App\Model\User;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher;
use Tymon\JWTAuth\JWTAuth;

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
        $userData['points'] = config('defaults.free_plan.points');

        $user = $this->repository->create($userData);

        dispatch(new SendRegistrationEmailJob($user['email'], ['data' => $user]));

        return $user;
    }

    public function createFromPanel(array $userData): array
    {
        $userData['role_id'] = $userData['role_id'] ?? Role::DEFAULT_ROLE;
        $userData['reset_token'] = uniqid('', true);
        $userData['points'] = config('defaults.free_plan.points');

        $user = $this->repository->create($userData);

        dispatch(new SendResetPasswordEmailJob($user['email'], ['data' => $user]));

        return $user;
    }

    public function createOrLoginFormSocial($user, string $provider)
    {
        $authService = app(JWTAuth::class);
        $userObject = $this->repository->findBySocialId($user->getId());
        $userData = [];

        if (!empty($userObject)) {
            return $authService->fromUser($userObject);
        }

        switch ($provider) {
            case 'google':
                $userData['google_id'] = $user->getId();
                break;
            case 'vkontakte':
                $userData['vk_id'] = $user->getId();
                break;
            case 'facebook':
                $userData['facebook_id'] = $user->getId();
                break;
            default:
                break;
        }

        if (!empty($user->getEmail()) && $this->repository->exists(['email' => $user->getEmail()])) {
            $updatedUser = $this->repository->updateBy(
                ['email' => $user->getEmail()],
                $userData,
                true
            );

            return $authService->fromUser(User::find($updatedUser['id']));
        } else {
            $userData['name'] = $user->getName();
            $userData['email'] = $user->getEmail();
            $userData['role_id'] = $userData['role_id'] ?? Role::DEFAULT_ROLE;
            $userData['points'] = config('defaults.free_plan.points');

            $createdUser = $this->repository->create($userData, false);
            return $authService->fromUser($createdUser);
        }
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

        $this->repository->updateBy(['reset_token' => $userData['hash']], $data, true);
    }

    public function search(int $currentUserId, ?array $filters = []): array
    {
        $filters['current_user_id'] = $currentUserId;
        $filters['with'] = ['plan'];

        return $this->repository->search($filters);
    }

    public function isLimitExceeded(int $userId, ?int $sendCount = 0)
    {
        $user = $this->repository->find($userId);

        if ($this->userSubscriptionActive($user)) {
            return false;
        }

        $limit = (int)array_get($user, 'points');

        return $sendCount > $limit;
    }

    public function hasActiveSubscription($userId)
    {
        $user = $this->repository->find($userId);

        return $this->userSubscriptionActive($user);
    }

    protected function userSubscriptionActive(array $user)
    {
        $subscriptionEnd = array_get($user, 'subscribed_before');

        if (null === $subscriptionEnd) {
            return false;
        }

        $now = Carbon::now();

        return $now->lessThan(Carbon::parse($subscriptionEnd));
    }
}

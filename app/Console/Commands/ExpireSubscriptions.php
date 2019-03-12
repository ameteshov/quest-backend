<?php

namespace App\Console\Commands;

use App\Model\Role;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class ExpireSubscriptions
 * @package App\Console\Commands
 * @property UserService userService
 */
class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Command expires subscriptions';

    protected $userService;

    public function __construct()
    {
        parent::__construct();

        $this->userService = app(UserService::class);
    }

    public function handle()
    {
        logger('Start expire users subscriptions');

        $users = $this->userService->getSubscribed();

        if ([] !== $users) {
            foreach ($users as $user) {
                if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse($user['subscribed_before']))) {
                    $this->userService->update($user['id'], ['subscribed_before' => null], Role::ROLE_ADMIN);
                }
            }
        } else {
            logger('No subscriptions to expire');
        }

        logger('Finish expire subscriptions');
    }
}

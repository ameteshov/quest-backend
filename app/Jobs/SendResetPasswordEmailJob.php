<?php

namespace App\Jobs;

class SendResetPasswordEmailJob extends SendEmailJob
{
    public function __construct(string $to, array $data)
    {
        parent::__construct(
            'emails.reset-password',
            'noreply',
            config('defaults.emails.support'),
            $to,
            $data
        );
    }
}

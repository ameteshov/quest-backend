<?php

namespace App\Jobs;

class SendRegistrationEmailJob extends SendEmailJob
{
    public function __construct(string $to, array $data)
    {
        parent::__construct(
            'emails.registration',
            'noreply',
            config('defaults.emails.support'),
            $to,
            $data
        );
    }
}

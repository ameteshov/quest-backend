<?php

namespace App\Jobs;

class SendFormEmailJob extends SendEmailJob
{
    public function __construct(string $to, array $data)
    {
        parent::__construct(
            'emails.form',
            trans('email.form.subject'),
            config('defaults.emails.support'),
            $to,
            $data
        );
    }
}

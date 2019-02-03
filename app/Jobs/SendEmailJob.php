<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data = [];
    protected $template = '';
    protected $to = '';
    protected $from = '';
    protected $subject = '';

    public function __construct(string $template, string $subject, string $from, string $to, array $data)
    {
        $this->data = $data;
        $this->template = $template;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
    }

    public function handle()
    {
        Mail::send($this->template, $this->data, function ($m) {
            $m->from($this->from, $this->subject);
            $m->to($this->to)->subject($this->subject);
        });
    }
}

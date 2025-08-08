<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class SendSmsCode extends Notification
{
    use Queueable;

    public function __construct(protected int $code) {}

    public function via($notifiable): array
    {
        return ['nexmo'];
    }

    public function toNexmo($notifiable): NexmoMessage
    {
        return (new NexmoMessage)->content("Your verification code is: {$this->code}");
    }
}

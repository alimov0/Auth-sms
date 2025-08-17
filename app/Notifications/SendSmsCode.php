<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendSmsCode extends Notification
{
    use Queueable;

    public function __construct(protected string $text, protected string $phone) {}

    public function via($notifiable): array
    {
        return ['sms'];
    }

    public function toSms($notifiable): array
    {
        return [
            'phone' => $this->phone,
            'text'  => $this->text,
        ];
    }
}

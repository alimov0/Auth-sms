<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $buyerName,   
        protected int $orderId,       
        protected int $amount          
    ) {}

    public function via($notifiable): array
    {
        return ['database']; // bazaga ham yozamiz. SMS’ni controllerda SmsService bilan jo‘natamiz
    }

    public function toArray($notifiable): array
    {
        return [
            'title'   => 'Yangi sotuv',
            'message' => "{$this->buyerName} sizning mahsulotingizni sotib oldi. Buyurtma #{$this->orderId}, summa: {$this->amount}",
        ];
    }
}

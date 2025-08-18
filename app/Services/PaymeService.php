<?php

namespace App\Services;

use App\Models\Order;

class PaymeService
{
    /**
     * Demo uchun to‘lov linkini qaytaradi.
     * Real hayotda Payme API orqali invoice yaratiladi.
     */
    public function createPaymentLink(Order $order): string
    {
        return url("/fake-payme/pay?order_id={$order->id}&amount={$order->total_amount}");
    }

    /**
     * Payme’dan kelgan callbackni tekshirish.
     * Demo’da doim TRUE qaytaramiz.
     */
    public function verifyCallback(array $payload): bool
    {
        return true;
    }
}

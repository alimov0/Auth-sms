<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderPaidNotification;
use App\Services\PaymeService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymeService $payme,
        protected SmsService $sms
    ) {}

    /**
     * Xaridor buyurtma uchun to‘lov sahifasiga link oladi.
     */
    public function pay(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return response()->json(['error' => 'Order is not available for payment'], 400);
        }

        $paymentLink = $this->payme->createPaymentLink($order);

        return response()->json([
            'payment_url' => $paymentLink
        ]);
    }

    /**
     * Payme callback (demo).
     * To‘lov muvaffaqiyatli bo‘lsa → order status PAID bo‘ladi.
     * Sotuvchiga notification + SMS yuboriladi.
     */
    public function paymeCallback(Request $request)
    {
        $payload = $request->all();

        if (! $this->payme->verifyCallback($payload)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $orderId       = (int) ($payload['order_id'] ?? 0);
        $transactionId = $payload['transaction_id'] ?? null;

        $order = Order::with('product.user', 'user')->find($orderId);

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Agar allaqachon to‘langan bo‘lsa → qayta ishlamaymiz (idempotent)
        if ($order->status === Order::STATUS_PAID) {
            return response()->json(['message' => 'Already paid']);
        }

        //  To‘lov qabul qilindi
        $order->update([
            'status' => Order::STATUS_PAID,
            'payme_transaction_id' => $transactionId,
        ]);

        $seller = $order->product->user; // Axmad (sotuvchi)
        $buyer  = $order->user;          // Laylo (xaridor)

        // 1) Notification
        $seller->notify(
            new OrderPaidNotification(
                $buyer->name ?? 'Xaridor',
                $order->id,
                $order->total_amount
            )
        );

        //  2) SMS
        $message = "Sizning mahsulotingiz sotildi! Buyurtma #{$order->id}. Summa: {$order->total_amount}. Xaridor: {$buyer->name}";
        $this->sms->send($seller->phone, $message);

        return response()->json(['message' => 'Payment processed and seller notified']);
    }
}

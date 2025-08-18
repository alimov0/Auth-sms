<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymeService;

class OrderController extends Controller
{
    public function __construct(protected PaymeService $payme) {} 

    public function store(OrderStoreRequest $request)
    {
        $user = auth()->user();
        $product = Product::with('user')->findOrFail($request->input('product_id'));

        if ($product->user_id === $user->id) {
            return $this->error('O‘zingizning mahsulotingizni sotib olmaysiz', 422); 
        }

        // total_amount -> mahsulot narxi (xavfsizlik uchun backend belgilaydi)
        $order = Order::create([
            'user_id'      => $user->id,
            'product_id'   => $product->id,
            'total_amount' => $product->price,
            'address'      => $request->input('address'),
            'status'       => Order::STATUS_PENDING,
        ]);

        $order->load('product.user');

        $payUrl = $this->payme->createPaymentLink($order); 

        return $this->success([
            'order'   => new OrderResource($order),
            'pay_url' => $payUrl,
        ], 'Order created. Proceed to payment', 201);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && $order->product->user_id !== auth()->id()) {
            return $this->error('Access denied', 403); 
        }

        $order->load('product.user');
        return $this->success(new OrderResource($order), 'Order detail');
    }
}

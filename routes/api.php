<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;   
use App\Http\Controllers\Api\OrderController;     
use App\Http\Controllers\Api\PaymentController;   
use App\Http\Controllers\Api\AuthController;

//  Auth 
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/verify',   [AuthController::class, 'verify']);
Route::post('/auth/login/send-code',   [AuthController::class, 'sendCode']);
Route::post('/auth/login/verify-code', [AuthController::class, 'verifyCode']);
Route::post('/auth/resend-code',       [AuthController::class, 'resendCode']);
Route::post('/auth/change-phone',      [AuthController::class, 'changePhone'])->middleware('auth:sanctum');
Route::post('/auth/confirm-phone',     [AuthController::class, 'confirmPhone'])->middleware('auth:sanctum');

// Products
Route::get('/products',        [ProductController::class, 'index']);  
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products',                    [ProductController::class, 'store']);   
    Route::put('/products/{product}',           [ProductController::class, 'update']);  
    Route::delete('/products/{product}',        [ProductController::class, 'destroy']); 

    //  Orders
    Route::post('/orders',                      [OrderController::class, 'store']);     
    Route::get('/orders/{order}',               [OrderController::class, 'show']);      
});

//  Payme callback (auth talab qilinmaydi — Payme serveri uradi)
Route::post('/payments/payme/callback', [PaymentController::class, 'paymeCallback']); 

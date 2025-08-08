<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    // 1. Ro‘yxatdan o‘tish (register)
    Route::post('/register', [AuthController::class, 'register']);

    // SMS kodni tasdiqlash (ro‘yxatdan o‘tishda)
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);

    // 2. Login qilish
    Route::post('/login', [AuthController::class, 'login']);

    // Login SMS kodini tasdiqlash
    Route::post('/confirm-login', [AuthController::class, 'confirmLogin']);

    // 3. SMS kodni qayta yuborish
    Route::post('/resend-code', [AuthController::class, 'resendCode']);

    // 4. Telefon raqamini almashtirish
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/change-phone/send-code', [AuthController::class, 'sendChangePhoneCode']);
        Route::post('/change-phone/confirm', [AuthController::class, 'confirmChangePhone']);
    });
});

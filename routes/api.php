<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
Route::prefix('auth')->group(function () {
    //  Ro‘yxatdan o‘tish
    Route::post('/register', [AuthController::class, 'register']);

    //  Ro‘yxatni tasdiqlash kodi
    Route::post('/verify', [AuthController::class, 'verify']);

    //  Login uchun kod yuborish
    Route::post('/send-code', [AuthController::class, 'sendCode']);

    //  Login kodini tasdiqlash
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);

    //  Kodni qayta yuborish
    Route::post('/resend-code', [AuthController::class, 'resendCode']);

    //  Raqamni yangilash
    Route::post('/change-phone', [AuthController::class, 'changePhone']);

    //  Yangi raqamni tasdiqlash
    Route::post('/confirm-phone', [AuthController::class, 'confirmPhone']);
});

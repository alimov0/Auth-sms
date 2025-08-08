<?php

namespace App\Services;

use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\ResendCodeDTO;
use App\DTO\Auth\ChangePhoneRequestDTO;
use App\DTO\Auth\ConfirmPhoneChangeDTO;
use App\DTO\Auth\CleanUnverifiedUsersDTO;

use App\Interfaces\Services\AuthServiceInterface;
use App\Interfaces\Repositories\AuthRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;

use App\Notifications\SendSmsCode;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\JsonResponse;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterDTO $dto): JsonResponse
    {
        $user = $this->authRepository->create($dto);

        $code = rand(10000, 99999);
        $cacheKey = 'sms_code_' . $user->phone;
        Cache::put($cacheKey, $code, now()->addMinute());

        Notification::route('nexmo', $user->phone)->notify(new SendSmsCode($code));

        return response()->json([
            'status' => 'success',
            'message' => 'SMS code sent.',
            'data' => [
                'user_id' => $user->id,
            ],
        ]);
    }
    public function login(LoginDTO $dto): JsonResponse
    {
        $user = $this->userRepository->findByPhone($dto->phone);

        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User not found.',
            ], 404);
        }

        if (!$user->is_verified) {
            return response()->json([
                'status' => 'success',
                'message' => 'Phone number not verified.',
            ], 403);
        }

        $cacheKey = 'sms_code_' . $user->phone;
        if (Cache::has($cacheKey)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Please wait before requesting another code.',
            ], 429);
        }

        $code = rand(10000, 99999);
        Cache::put($cacheKey, $code, now()->addMinute());

        Notification::route('nexmo', $user->phone)->notify(new SendSmsCode($code));

        return response()->json([
            'status' => 'success',
            'message' => 'Login verification code sent.',
            'expires_at' => now()->addMinute()->toDateTimeString(),
        ]);
    }
    public function resendCode(ResendCodeDTO $dto): JsonResponse
    {
        $cacheKey = 'sms_code_' . $dto->phone;

        if (Cache::has($cacheKey)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Please wait before requesting another code.',
            ], 429);
        }

        $code = rand(10000, 99999);
        Cache::put($cacheKey, $code, now()->addMinute());

        Notification::route('nexmo', $dto->phone)->notify(new SendSmsCode($code));

        return response()->json([
            'status' => 'success',
            'message' => 'Verification code resent.',
            'expires_at' => now()->addMinute()->toDateTimeString(),
        ]);
    }
    public function sendChangePhoneCode(ChangePhoneRequestDTO $dto): JsonResponse
    {
        $cacheKey = 'change_phone_' . $dto->new_phone;

        if (Cache::has($cacheKey)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Code already sent. Try again after 1 minute.',
            ], 429);
        }

        $code = rand(10000, 99999);
        Cache::put($cacheKey, $code, now()->addMinute());

        Notification::route('nexmo', $dto->new_phone)->notify(new SendSmsCode($code));

        return response()->json([
            'status' => 'success',
            'message' => 'Verification code sent to new phone number.',
            'expires_at' => now()->addMinute()->toDateTimeString(),
        ]);
    }
    public function confirmChangePhone(ConfirmPhoneChangeDTO $dto): JsonResponse
    {
        $cacheKey = 'change_phone_' . $dto->new_phone;
        $cachedCode = Cache::get($cacheKey);

        if (!$cachedCode || $cachedCode != $dto->code) {
            return response()->json([
                'status' => 'success',
                'message' => 'Invalid or expired code.',
            ], 400);
        }

        $user = Auth::user();
        $user->phone = $dto->new_phone;
        $user->save();

        Cache::forget($cacheKey);

        return response()->json([
            'status' => 'success',
            'message' => 'Phone number changed successfully.',
        ]);
    }
    public function cleanUnverifiedUsers(): void
    {
        $dto = new CleanUnverifiedUsersDTO();
        $deletedCount = $this->userRepository->deleteUnverifiedUsersOlderThan($dto);

        Log::info("Cleaned {$deletedCount} unverified users older than 3 days.");
    }
}

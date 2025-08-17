<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\ResendCodeDTO;
use App\DTO\Auth\VerifyCodeDTO;
use App\DTO\Auth\ChangePhoneRequestDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\Services\AuthServiceInterface;
use App\Interfaces\Repositories\AuthRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository,
        protected UserRepositoryInterface $userRepository
    ) {}

    // 1. Ro‘yxatdan o‘tish
    public function register(RegisterDTO $dto): User
    {
        $user = $this->authRepository->create($dto);
        $this->sendVerifyCode($user);
        return $user;
    }

    public function sendVerifyCode(User $user): void
    {
        $code = random_int(10000, 99999);

        // 1 daqiqada qayta yuborishni cheklash
        if (Cache::has("verify_code_{$user->phone}")) {
            return;
        }

        Cache::put("verify_code_{$user->phone}", $code, now()->addMinute());
        $this->sendSms($user->phone, "Afisha Market MCHJ Tasdiqlovchi kodni kiriting: {$code}");
    }

    public function verifyCode(VerifyCodeDTO $dto): bool
    {
        $user = $this->userRepository->findByPhone($dto->phone);
        if (! $user) return false;

        $cached = Cache::get("verify_code_{$user->phone}");
        if (! $cached || $cached !== $dto->code) return false;

        $user->is_verified = true;
        $user->save();
        Cache::forget("verify_code_{$user->phone}");

        return true;
    }

    // 2. Login uchun kod yuborish
    public function sendLoginCode(LoginDTO $dto): ?User
    {
        $user = $this->authRepository->findByPhone($dto->phone);
        if (! $user || ! $user->is_verified) return null;

        // cheklash
        if (Cache::has("login_code_{$dto->phone}")) {
            return $user;
        }

        $code = random_int(10000, 99999);
        Cache::put("login_code_{$dto->phone}", $code, now()->addMinute());
        $this->sendSms($dto->phone, "Afisha Market MCHJ Tasdiqlovchi kodni kiriting: {$code}");

        return $user;
    }

    public function verifyLoginCode(string $phone, string $code): ?string
    {
        $cachedCode = Cache::get("login_code_{$phone}");
        if ($cachedCode && $cachedCode == $code) {
            $user = $this->authRepository->findByPhone($phone);
            if (! $user) return null;

            Cache::forget("login_code_{$phone}");
            return $user->createToken('api_token')->plainTextToken;
        }
        return null;
    }

    // 3. Kodni qayta yuborish (cache bilan)
    public function resendCode(ResendCodeDTO $dto): array
    {
        $user = $this->userRepository->findByPhone($dto->phone);
        if (! $user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if (Cache::has("verify_code_{$user->phone}")) {
            return ['success' => false, 'message' => '1 daqiqa ichida kod yuborilgan'];
        }

        $code = random_int(10000, 99999);
        Cache::put("verify_code_{$user->phone}", $code, now()->addMinute());
        $this->sendSms($user->phone, "Afisha Market MCHJ Tasdiqlovchi kodni kiriting: {$code}");

        return ['success' => true, 'message' => 'Kod qayta yuborildi', 'user' => $user];
    }

    // 4. Telefon raqamini o‘zgartirish
    public function changePhone(ChangePhoneRequestDTO $dto): array
    {
        $exists = $this->userRepository->findByPhone($dto->newPhone);
        if ($exists) {
            return ['success' => false, 'message' => 'Phone already registered'];
        }

        $user = auth()->user();

        if (Cache::has("change_phone_code_{$dto->newPhone}")) {
            return ['success' => false, 'message' => '1 daqiqa ichida kod yuborilgan'];
        }

        $code = random_int(10000, 99999);
        Cache::put("change_phone_code_{$dto->newPhone}", $code, now()->addMinute());
        $this->sendSms($dto->newPhone, "Afisha Market MCHJ Tasdiqlovchi kodni kiriting: {$code}");

        return ['success' => true, 'message' => 'Kod yuborildi', 'user' => $user];
    }

    public function confirmPhone(string $code): array
    {
        $user = auth()->user();
        $cached = Cache::get("change_phone_code_{$user->phone}");

        if (! $cached || $cached !== $code) {
            return ['success' => false, 'message' => 'Kod noto‘g‘ri yoki muddati tugagan'];
        }

        Cache::forget("change_phone_code_{$user->phone}");
        $this->userRepository->confirmPhone($user);

        return ['success' => true, 'message' => 'Telefon tasdiqlandi', 'user' => $user];
    }

    //  SMS yuborish
    private function getToken(): ?string
    {
        $token = Cache::get('eskiz_api_token');
        if (! $token) {
            $response = Http::post('https://notify.eskiz.uz/api/auth/login', [
                'email'    => config('eskiz.eskiz_sms_login'),
                'password' => config('eskiz.eskiz_sms_password'),
            ]);

            $token = $response['data']['token'] ?? null;
            if ($token) {
                Cache::put('eskiz_api_token', $token, now()->addDays(29));
            }
        }
        return $token;
    }

    private function sendSms(string $phone, string $text): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
        ])->post('https://notify.eskiz.uz/api/message/sms/send', [
            'mobile_phone' => $phone,
            'message'      => $text,
            'from'         => '4546',
        ]);
    }
}

<?php

namespace App\Interfaces\Services;

use App\Models\User;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\ResendCodeDTO;
use App\DTO\Auth\VerifyCodeDTO;
use App\DTO\Auth\ChangePhoneRequestDTO;

interface AuthServiceInterface
{
    // 1. Ro‘yxatdan o‘tish
    public function register(RegisterDTO $dto): User;
    public function sendVerifyCode(User $user): void;
    public function verifyCode(VerifyCodeDTO $dto): bool;

    // 2. Login
    public function sendLoginCode(LoginDTO $dto): ?User;
    public function verifyLoginCode(string $phone, string $code): ?string;

    // 3. Kodni qayta yuborish
    public function resendCode(ResendCodeDTO $dto): array;

    // 4. Telefon raqamini o‘zgartirish
    public function changePhone(ChangePhoneRequestDTO $dto): array;
    public function confirmPhone(string $code): array;
}

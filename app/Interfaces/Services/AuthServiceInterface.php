<?php

namespace App\Interfaces\Services;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\ResendCodeDTO;
use App\DTO\Auth\ChangePhoneRequestDTO;
use App\DTO\Auth\ConfirmPhoneChangeDTO;
use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): JsonResponse;
    public function login(LoginDTO $dto): JsonResponse;
    public function resendCode(ResendCodeDTO $dto): JsonResponse;
    public function sendChangePhoneCode(ChangePhoneRequestDTO $dto): JsonResponse;
    public function confirmChangePhone(ConfirmPhoneChangeDTO $dto): JsonResponse;
}

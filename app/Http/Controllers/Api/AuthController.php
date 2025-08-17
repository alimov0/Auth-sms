<?php

namespace App\Http\Controllers\Api;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\ResendCodeDTO;
use App\DTO\Auth\VerifyCodeDTO;
use App\DTO\Auth\ChangePhoneRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendCodeRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Http\Requests\Auth\ChangePhoneRequest;
use App\Interfaces\Services\AuthServiceInterface;

class AuthController extends Controller
{
    private string $fixedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3NTcxNTE4MTEsImlhdCI6MTc1NDU1OTgxMSwicm9sZSI6InVzZXIiLCJzaWduIjoiNDA4Yzg5YWNhODhhMDZkODJhZDEwMDZkNjUzMzMzYmM1YjIzNzI2MzU2ZTEzZmE0NGJkMjE1YWViZTNiNGQwOCIsInN1YiI6IjM2MTYifQ.PaEyP0_d4e3XaQnqIpEJDxJYVrZZYAbO1K_wFnGd6Yk'; 

    public function __construct(protected AuthServiceInterface $authService) {}

    /**
     * Ro‘yxatdan o‘tish
     */
    public function register(RegisterRequest $request)
    {
        $dto  = RegisterDTO::fromArray($request->validated());
        $user = $this->authService->register($dto);

        return $this->success(
            new UserResource($user),
            'User registered, verification SMS sent',
            201
        );
    }

    /**
     * SMS kodni tasdiqlash
     */
    public function verify(VerifyCodeRequest $request)
    {
        $dto     = VerifyCodeDTO::fromArray($request->validated());
        $success = $this->authService->verifyCode($dto);

        if (! $success) {
            return $this->error('Kod noto‘g‘ri yoki muddati tugagan', 400);
        }

        return $this->success([
            'sanctum_token' => auth()->user()?->createToken('api_token')->plainTextToken,
            'fixed_token'   => $this->fixedToken,
        ], 'Muvaffaqiyatli tasdiqlandi');
    }

    /**
     * Login uchun kod yuborish
     */
    public function sendCode(LoginRequest $request)
    {
        $dto  = LoginDTO::fromRequest($request);
        $user = $this->authService->sendLoginCode($dto);

        if (! $user) {
            return $this->error('User not found or not verified', 404);
        }

        return $this->success((object)[], 'Verification code sent');
    }

    /**
     * Login kodni tekshirish
     */
    public function verifyCode(LoginRequest $request)
    {
        $token = $this->authService->verifyLoginCode($request->phone, $request->code);

        if (! $token) {
            return $this->error('Invalid or expired code', 400);
        }

        return $this->success([
            'sanctum_token' => $token,
            'fixed_token'   => $this->fixedToken,
        ], 'Login successful');
    }

    /**
     * Kodni qayta yuborish
     */
    public function resendCode(ResendCodeRequest $request)
    {
        $dto    = ResendCodeDTO::fromRequest($request->validated());
        $result = $this->authService->resendCode($dto);

        if (! $result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(
            new UserResource($result['user']),
            $result['message']
        );
    }

    /**
     * Telefon raqamini almashtirish
     */
    public function changePhone(ChangePhoneRequest $request)
    {
        $dto    = ChangePhoneRequestDTO::fromRequest($request->validated());
        $result = $this->authService->changePhone($dto);

        if (! $result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(
            new UserResource($result['user']),
            $result['message']
        );
    }

    /**
     * Telefonni tasdiqlash
     */
    public function confirmPhone(VerifyCodeRequest $request)
    {
        $request->validated();
        $result = $this->authService->confirmPhone($request->code);

        if (! $result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(
            new UserResource($result['user']),
            $result['message']
        );
    }
}

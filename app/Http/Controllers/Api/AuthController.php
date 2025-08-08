<?php

namespace App\Http\Controllers\Api;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\ResendCodeDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\DTO\Auth\ChangePhoneRequestDTO;
use App\DTO\Auth\ConfirmPhoneChangeDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\ConfirmPhoneChangeRequest;
use App\Http\Requests\SendPhoneChangeCodeRequest;
use App\Interfaces\Services\AuthServiceInterface;

class AuthController extends Controller
{
    public function __construct(protected AuthServiceInterface $authService) {}

    /**
     * Register new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterDTO::fromArray($request->validated());
        return $this->authService->register($dto);
    }

    /**
     * Login user by phone
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = new LoginDTO($request->validated());
        return $this->authService->login($dto);
    }
    public function resendCode(ResendCodeRequest $request)
    {
        $dto = new ResendCodeDTO($request->validated());
        return $this->authService->resendCode($dto);
    }
    public function sendPhoneChangeCode(SendPhoneChangeCodeRequest $request)
    {
        $dto = new ChangePhoneRequestDTO($request->validated());
        return $this->authService->sendChangePhoneCode($dto);
    }
    
    public function confirmPhoneChange(ConfirmPhoneChangeRequest $request)
    {
        $dto = new ConfirmPhoneChangeDTO($request->validated());
        return $this->authService->confirmChangePhone($dto);
    }


}

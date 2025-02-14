<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\DTO\Auth\LoginDto;
use App\DTO\Auth\RegisterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = new RegisterDto(
            $request->name,
            $request->email,
            $request->password
        );

        return $this->authService->register($dto);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = new LoginDto(
            $request->email,
            $request->password
        );

        return $this->authService->login($dto);
    }
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    public function profile(): JsonResponse
    {
        return $this->authService->profile();
    }
}
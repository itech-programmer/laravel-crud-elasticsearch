<?php

namespace App\Contracts\Auth;

use App\DTO\Auth\LoginDto;
use App\DTO\Auth\RegisterDto;
use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function register(RegisterDto $dto): JsonResponse;
    public function login(LoginDto $dto): JsonResponse;
    public function logout(): JsonResponse;
    public function profile(): JsonResponse;
}
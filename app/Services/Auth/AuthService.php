<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthRepositoryInterface;
use App\Contracts\Auth\AuthServiceInterface;
use App\Models\User;
use App\DTO\Auth\LoginDto;
use App\DTO\Auth\RegisterDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Services\ApiResponseService;

class AuthService implements AuthServiceInterface
{
    private AuthRepositoryInterface $authRepository;
    private ApiResponseService $apiResponse;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        ApiResponseService $apiResponse
    )
    {
        $this->authRepository = $authRepository;
        $this->apiResponse = $apiResponse;
    }

    public function register(RegisterDto $dto): JsonResponse
    {
        $user = $this->authRepository->createUser([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        $token = Auth::guard('api')->attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return $this->apiResponse->success('User registered successfully', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'token' => $token,
        ], 201);
    }
    public function login(LoginDto $dto): JsonResponse
    {
        if (!$token = Auth::guard('api')->attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ])) {
            return $this->apiResponse->error('Unauthorized', [], 401);
        }

        return $this->apiResponse->success('Login successful', ['token' => $token]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return $this->apiResponse->success('Successfully logged out');
    }

    public function profile(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return $this->apiResponse->error('Unauthorized', 401);
        }

        return $this->apiResponse->success(
            'User data', $user
        );
    }
}
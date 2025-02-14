<?php

namespace Services\Auth;

use App\Contracts\Auth\AuthRepositoryInterface;
use App\Contracts\Auth\AuthServiceInterface;
use App\DTO\Auth\LoginDto;
use App\DTO\Auth\RegisterDto;
use App\Models\User;
use App\Services\ApiResponseService;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthServiceInterface $authService;
    private $apiResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepositoryMock = Mockery::mock(AuthRepositoryInterface::class);
        $this->apiResponseMock = Mockery::mock(ApiResponseService::class);

        $this->authService = new AuthService($this->authRepositoryMock, $this->apiResponseMock);
    }

    public function test_register_success(): void
    {
        $dto = new RegisterDto('John Doe', 'john@example.com', 'password');

        $mockedUser = new User([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->authRepositoryMock->shouldReceive('createUser')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($mockedUser);

        Auth::shouldReceive('guard->attempt')
            ->once()
            ->andReturn('mocked_token');

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('User registered successfully', Mockery::type('array'), 201)
            ->andReturn(response()->json(['message' => 'User registered successfully'], 201));

        $response = $this->authService->register($dto);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_login_success(): void
    {
        $dto = new LoginDto('john@example.com', 'password');

        Auth::shouldReceive('guard->attempt')
            ->once()
            ->andReturn('mocked_token');

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Login successful', ['token' => 'mocked_token'])
            ->andReturn(response()->json(['message' => 'Login successful'], 200));

        $response = $this->authService->login($dto);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_login_failure(): void
    {
        $dto = new LoginDto('john@example.com', 'wrongpassword');

        Auth::shouldReceive('guard->attempt')
            ->once()
            ->andReturn(false);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Unauthorized', [], 401)
            ->andReturn(response()->json(['message' => 'Unauthorized'], 401));

        $response = $this->authService->login($dto);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_logout(): void
    {
        Auth::shouldReceive('guard->logout')
            ->once();

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Successfully logged out')
            ->andReturn(response()->json(['message' => 'Successfully logged out'], 200));

        $response = $this->authService->logout();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_profile_authenticated(): void
    {
        $user = new User(['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com']);

        Auth::shouldReceive('guard->user')
            ->once()
            ->andReturn($user);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('User data', $user)
            ->andReturn(response()->json(['message' => 'User data'], 200));

        $response = $this->authService->profile();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_profile_unauthenticated(): void
    {
        Auth::shouldReceive('guard->user')
            ->once()
            ->andReturn(null);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Unauthorized', 401)
            ->andReturn(response()->json(['message' => 'Unauthorized'], 401));

        $response = $this->authService->profile();

        $this->assertEquals(401, $response->getStatusCode());
    }
}

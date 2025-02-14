<?php

namespace App\Repositories\Auth;

use App\Contracts\Auth\AuthRepositoryInterface;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }
}
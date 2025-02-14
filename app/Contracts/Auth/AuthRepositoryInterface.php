<?php

namespace App\Contracts\Auth;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function createUser(array $data): User;
}
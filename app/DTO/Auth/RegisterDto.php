<?php

namespace App\DTO\Auth;

class RegisterDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}
}
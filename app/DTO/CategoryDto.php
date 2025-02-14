<?php

namespace App\DTO;

class CategoryDto
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $image
    ) {}
}
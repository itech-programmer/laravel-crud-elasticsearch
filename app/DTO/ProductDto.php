<?php

namespace App\DTO;

class ProductDto
{
    public function __construct(
        public string $title,
        public float $price,
        public ?string $image,
        public ?string $description,
        public int $category_id
    ) {}
}
<?php

namespace App\Contracts\Category;

use App\DTO\CategoryDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function getAll(): JsonResponse;
    public function findById(int $id): JsonResponse;
    public function create(CategoryDto $dto): JsonResponse;
    public function update(int $id, CategoryDto $dto): JsonResponse;
    public function delete(int $id): JsonResponse;
}
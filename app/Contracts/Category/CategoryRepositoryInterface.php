<?php

namespace App\Contracts\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Category;
    public function create(array $data): Category;
    public function update(int $id, array $data): ?Category;
    public function delete(int $id): bool;
}
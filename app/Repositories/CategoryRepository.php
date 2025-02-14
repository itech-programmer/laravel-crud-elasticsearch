<?php

namespace App\Repositories;

use App\Contracts\Category\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::all();
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): ?Category
    {
        $category = Category::find($id);
        if ($category) {
            $category->update($data);
        }
        return $category;
    }

    public function delete(int $id): bool
    {
        return Category::destroy($id) > 0;
    }
}
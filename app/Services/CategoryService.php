<?php

namespace App\Services;

use App\Contracts\ApiResponseServiceInterface;
use App\Contracts\Category\CategoryRepositoryInterface;
use App\Contracts\Category\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use App\DTO\CategoryDto;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepositoryInterface $categoryRepository;
    private ApiResponseServiceInterface $apiResponse;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ApiResponseServiceInterface $apiResponse
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->apiResponse = $apiResponse;
    }

    public function getAll(): JsonResponse
    {
        return $this->apiResponse->success('Categories retrieved successfully', $this->categoryRepository->getAll());
    }

    public function findById(int $id): JsonResponse
    {
        $category = $this->categoryRepository->findById($id);

        return $category
            ? $this->apiResponse->success('Category found', [
                $category
            ])
            : $this->apiResponse->error('Category not found', 404);
    }

    public function create(CategoryDto $dto): JsonResponse
    {
        $category = $this->categoryRepository->create([
            'title' => $dto->title,
            'description' => $dto->description,
            'image' => $dto->image,
        ]);

        return $this->apiResponse->success('Category created successfully', $category, 201);
    }

    public function update(int $id, CategoryDto $dto): JsonResponse
    {
        $category = $this->categoryRepository->update($id, [
            'title' => $dto->title,
            'description' => $dto->description,
            'image' => $dto->image,
        ]);

        return $category
            ? $this->apiResponse->success('Category updated successfully', $category)
            : $this->apiResponse->error('Category not found', 404);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->categoryRepository->delete($id)
            ? $this->apiResponse->success('Category deleted successfully')
            : $this->apiResponse->error('Category not found', 404);
    }
}

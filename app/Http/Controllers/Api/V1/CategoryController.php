<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Category\CategoryServiceInterface;
use App\DTO\CategoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryServiceInterface $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        return $this->categoryService->getAll();
    }

    public function show(int $id): JsonResponse
    {
        return $this->categoryService->findById($id);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $dto = new CategoryDto(
            $request->validated()['title'],
            $request->validated()['description'] ?? null,
            $request->validated()['image'] ?? null
        );

        return $this->categoryService->create($dto);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $dto = new CategoryDto(
            $request->validated()['title'],
            $request->validated()['description'] ?? null,
            $request->validated()['image'] ?? null
        );

        return $this->categoryService->update($id, $dto);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->delete($id);
    }
}

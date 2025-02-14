<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Product\ProductServiceInterface;
use App\DTO\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        return $this->productService->getAll();
    }

    public function show(int $id): JsonResponse
    {
        return $this->productService->findById($id);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $dto = new ProductDto(
            title: $request->validated()['title'],
            price: $request->validated()['price'],
            image: $request->validated()['image'] ?? null,
            description: $request->validated()['description'] ?? null,
            category_id: $request->validated()['category_id']
        );

        return $this->productService->create($dto);
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $dto = new ProductDto(
            title: $request->validated()['title'],
            price: $request->validated()['price'],
            image: $request->validated()['image'] ?? null,
            description: $request->validated()['description'] ?? null,
            category_id: $request->validated()['category_id']
        );

        return $this->productService->update($id, $dto);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->productService->delete($id);
    }
}

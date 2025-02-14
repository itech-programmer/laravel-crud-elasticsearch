<?php

namespace App\Services;

use App\Contracts\ApiResponseServiceInterface;
use App\Contracts\ElasticsearchServiceInterface;
use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use App\DTO\ProductDto;

class ProductService implements ProductServiceInterface
{
    private ProductRepositoryInterface $productRepository;
    private ElasticsearchServiceInterface $elasticsearchService;
    private ApiResponseServiceInterface $apiResponse;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ElasticsearchServiceInterface $elasticsearchService,
        ApiResponseServiceInterface $apiResponse
    ) {
        $this->productRepository = $productRepository;
        $this->elasticsearchService = $elasticsearchService;
        $this->apiResponse = $apiResponse;
    }

    public function getAll(): JsonResponse
    {
        return $this->apiResponse->success('Products retrieved successfully', $this->productRepository->getAll());
    }

    public function findById(int $id): JsonResponse
    {
        $product = $this->productRepository->findById($id);

        return $product
            ? $this->apiResponse->success('Product found', $product)
            : $this->apiResponse->error('Product not found', 404);
    }

    public function search(string $query): JsonResponse
    {
        if (!$query) {
            return $this->apiResponse->error('Query parameter is required', 400);
        }
        return $this->apiResponse->success('Search results', $this->elasticsearchService->search($query));
    }

    public function create(ProductDto $dto): JsonResponse
    {
        $product = $this->productRepository->create([
            'title' => $dto->title,
            'price' => $dto->price,
            'image' => $dto->image,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
        ]);

        $this->elasticsearchService->indexProduct($product);

        return $this->apiResponse->success('Product created successfully', $product, 201);
    }

    public function update(int $id, ProductDto $dto): JsonResponse
    {
        $product = $this->productRepository->update($id, [
            'title' => $dto->title,
            'price' => $dto->price,
            'image' => $dto->image,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
        ]);

        return $product
            ? $this->apiResponse->success('Product updated successfully', $product)
            : $this->apiResponse->error('Product not found', 404);
    }

    public function delete(int $id): JsonResponse
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return $this->apiResponse->error('Product not found', 404);
        }

        $this->elasticsearchService->deleteProduct($product);
        $this->productRepository->delete($id);

        return $this->apiResponse->success('Product deleted successfully');
    }
}

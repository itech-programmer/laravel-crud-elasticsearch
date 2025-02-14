<?php

namespace Services;

use App\Contracts\ApiResponseServiceInterface;
use App\Contracts\ElasticsearchServiceInterface;
use App\Contracts\Product\ProductRepositoryInterface;
use App\DTO\ProductDto;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private $productRepositoryMock;
    private $elasticsearchServiceMock;
    private $apiResponseMock;
    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = Mockery::mock(ProductRepositoryInterface::class);
        $this->elasticsearchServiceMock = Mockery::mock(ElasticsearchServiceInterface::class);
        $this->apiResponseMock = Mockery::mock(ApiResponseServiceInterface::class);

        $this->productService = new ProductService(
            $this->productRepositoryMock,
            $this->elasticsearchServiceMock,
            $this->apiResponseMock
        );
    }

    public function test_get_all_success(): void
    {
        $mockCollection = new Collection();

        $this->productRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn($mockCollection);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Products retrieved successfully', $mockCollection)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->getAll();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_find_by_id_success(): void
    {
        $product = Mockery::mock(Product::class);
        $this->productRepositoryMock->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Product found', $product)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->findById(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_find_by_id_not_found(): void
    {
        $this->productRepositoryMock->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Product not found', 404)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->findById(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_create(): void
    {
        $dto = new ProductDto(
            'Product Name',
            100,
            'image.jpg',
            'Description',
            1
        );

        $product = Mockery::mock(Product::class);
        $this->productRepositoryMock->shouldReceive('create')
            ->once()
            ->with([
                'title' => 'Product Name',
                'price' => 100,
                'image' => 'image.jpg',
                'description' => 'Description',
                'category_id' => 1
            ])
            ->andReturn($product);

        $this->elasticsearchServiceMock->shouldReceive('indexProduct')
            ->once()
            ->with($product);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Product created successfully', $product, 201)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->create($dto);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_update_success(): void
    {
        $dto = new ProductDto(
            'Updated Product Name',
            120,
            'updated-image.jpg',
            'Updated description',
            2
        );

        $product = Mockery::mock(Product::class);
        $this->productRepositoryMock->shouldReceive('update')
            ->once()
            ->with(1, [
                'title' => 'Updated Product Name',
                'price' => 120,
                'image' => 'updated-image.jpg',
                'description' => 'Updated description',
                'category_id' => 2
            ])
            ->andReturn($product);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Product updated successfully', $product)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->update(1, $dto);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_delete_success(): void
    {
        $product = Mockery::mock(Product::class);
        $this->productRepositoryMock->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->elasticsearchServiceMock->shouldReceive('deleteProduct')
            ->once()
            ->with($product);

        $this->productRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Product deleted successfully')
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->delete(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_delete_not_found(): void
    {
        $this->productRepositoryMock->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Product not found', 404)
            ->andReturn(Mockery::mock(JsonResponse::class));

        $response = $this->productService->delete(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

}

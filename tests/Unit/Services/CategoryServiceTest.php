<?php

namespace Services;

use App\Contracts\Category\CategoryRepositoryInterface;
use App\DTO\CategoryDto;
use App\Models\Category;
use App\Services\ApiResponseService;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    private CategoryService $categoryService;
    private $categoryRepositoryMock;
    private $apiResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryRepositoryMock = Mockery::mock(CategoryRepositoryInterface::class);
        $this->apiResponseMock = Mockery::mock(ApiResponseService::class);
        $this->categoryService = new CategoryService($this->categoryRepositoryMock, $this->apiResponseMock);
    }

    public function test_get_all_categories(): void
    {
        $categories = new Collection([
            new Category(['id' => 1, 'title' => 'Smartphones']),
            new Category(['id' => 2, 'title' => 'Laptops'])
        ]);

        $this->categoryRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn($categories);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Categories retrieved successfully', $categories)
            ->andReturn(response()->json(['message' => 'Categories retrieved successfully'], 200));

        $response = $this->categoryService->getAll();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_find_category_by_id_success(): void
    {
        $category = new Category(['id' => 1, 'title' => 'Smartphones']);

        $this->categoryRepositoryMock->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($category);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Category found', [$category])
            ->andReturn(response()->json(['message' => 'Category found'], 200));

        $response = $this->categoryService->findById(1);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_find_category_by_id_not_found(): void
    {
        $this->categoryRepositoryMock->shouldReceive('findById')
            ->with(99)
            ->once()
            ->andReturn(null);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Category not found', 404)
            ->andReturn(response()->json(['message' => 'Category not found'], 404));

        $response = $this->categoryService->findById(99);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_create_category_success(): void
    {
        $dto = new CategoryDto('Smartphones', 'Latest models', 'image.jpg');
        $category = new Category(['id' => 1, 'title' => 'Smartphones']);

        $this->categoryRepositoryMock->shouldReceive('create')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($category);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Category created successfully', $category, 201)
            ->andReturn(response()->json(['message' => 'Category created successfully'], 201));

        $response = $this->categoryService->create($dto);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_update_category_success(): void
    {
        $dto = new CategoryDto('Updated Smartphones', 'Updated description', 'updated.jpg');
        $category = new Category(['id' => 1, 'title' => 'Updated Smartphones']);

        $this->categoryRepositoryMock->shouldReceive('update')
            ->once()
            ->with(1, Mockery::type('array'))
            ->andReturn($category);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Category updated successfully', $category)
            ->andReturn(response()->json(['message' => 'Category updated successfully'], 200));

        $response = $this->categoryService->update(1, $dto);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_update_category_not_found(): void
    {
        $dto = new CategoryDto('Updated Smartphones', 'Updated description', 'updated.jpg');

        $this->categoryRepositoryMock->shouldReceive('update')
            ->once()
            ->with(99, Mockery::type('array'))
            ->andReturn(null);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Category not found', 404)
            ->andReturn(response()->json(['message' => 'Category not found'], 404));

        $response = $this->categoryService->update(99, $dto);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_delete_category_success(): void
    {
        $this->categoryRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->apiResponseMock->shouldReceive('success')
            ->once()
            ->with('Category deleted successfully')
            ->andReturn(response()->json(['message' => 'Category deleted successfully'], 200));

        $response = $this->categoryService->delete(1);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete_category_not_found(): void
    {
        $this->categoryRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(99)
            ->andReturn(false);

        $this->apiResponseMock->shouldReceive('error')
            ->once()
            ->with('Category not found', 404)
            ->andReturn(response()->json(['message' => 'Category not found'], 404));

        $response = $this->categoryService->delete(99);
        $this->assertEquals(404, $response->getStatusCode());
    }
}

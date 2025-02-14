<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Product\ProductServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private ProductServiceInterface $productService;

    public function __construct(
        ProductServiceInterface $productService
    )
    {
        $this->productService = $productService;
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query');
        return $this->productService->search($query);
    }
}
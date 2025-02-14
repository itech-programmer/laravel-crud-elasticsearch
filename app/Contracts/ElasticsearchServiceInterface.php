<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ElasticsearchServiceInterface
{
    public function indexProduct(Product $product): void;
    public function deleteProduct(Product $product): void;
    public function search(string $query, int $perPage = 10): LengthAwarePaginator;
}
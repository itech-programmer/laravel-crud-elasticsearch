<?php

namespace App\Services;

use App\Contracts\ElasticsearchServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Elastic\Elasticsearch\Client;
use App\Models\Product;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    private Client $client;

    public function __construct(
        Client $client
    )
    {
        $this->client = $client;
    }

    public function indexProduct(Product $product): void
    {
        $this->client->index([
            'index' => 'products',
            'id'    => $product->id,
            'body'  => [
                'title'       => $product->title,
                'description' => $product->description,
                'price'       => $product->price,
                'category_id' => $product->category_id,
            ],
        ]);
    }

    public function deleteProduct(Product $product): void
    {
        $this->client->delete([
            'index' => 'products',
            'id'    => $product->id,
        ]);
    }

    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        $response = $this->client->search([
            'index' => 'products',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['title', 'description']
                    ]
                ]
            ]
        ]);

        $hits = collect($response['hits']['hits'])->map(fn ($hit) => $hit['_source']);
        $total = is_array($response['hits']['total']) ? $response['hits']['total']['value'] : $response['hits']['total'];

        return new LengthAwarePaginator($hits, $total, $perPage);
    }
}
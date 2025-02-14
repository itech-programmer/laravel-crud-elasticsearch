<?php

namespace App\Providers;

use App\Contracts\ApiResponseServiceInterface;
use App\Contracts\Auth\AuthRepositoryInterface;
use App\Contracts\Auth\AuthServiceInterface;
use App\Contracts\Category\CategoryRepositoryInterface;
use App\Contracts\Category\CategoryServiceInterface;
use App\Contracts\ElasticsearchServiceInterface;
use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductServiceInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Services\ApiResponseService;
use App\Services\Auth\AuthService;
use App\Services\CategoryService;
use App\Services\ElasticsearchService;
use App\Services\ProductService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([config('services.elasticsearch.host')])
                ->build();
        });

        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->bind(ApiResponseServiceInterface::class, ApiResponseService::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ElasticsearchServiceInterface::class, ElasticsearchService::class);
    }

    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Repositories\Eloquent\CartItemRepository;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Eloquent\OrderItemRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->scoped(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->scoped(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->scoped(CartRepositoryInterface::class, CartRepository::class);
        $this->app->scoped(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->scoped(UserRepositoryInterface::class, UserRepository::class);
        $this->app->scoped(CartItemRepositoryInterface::class, CartItemRepository::class);
        $this->app->scoped(OrderItemRepositoryInterface::class, OrderItemRepository::class);
    }
}

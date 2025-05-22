<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Services\OrderService;
use App\Services\OrderServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Events\OrderCreated;
use App\Listeners\SendOrderConfirmMail;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class);

        $this->app->bind(
            OrderServiceInterface::class,
            OrderService::class);

        $this->app->bind(
            \App\Repositories\OrderRepositoryInterface::class,
            \App\Repositories\OrderRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            OrderCreated::class,
            SendOrderConfirmMail::class);
    }
}

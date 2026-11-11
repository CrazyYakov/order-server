<?php

declare(strict_types=1);

namespace Marketplace\Order\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Marketplace\Order\Core\Ports\CartReaderContract;
use Marketplace\Order\Core\Ports\OrderManagerContract;
use Marketplace\Order\Core\Ports\OrderRepositoryContract;
use Marketplace\Order\Infrastructure\Managers\OrderManager;
use Marketplace\Order\Infrastructure\Repositories\CartReaderRepository;
use Marketplace\Order\Infrastructure\Repositories\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(
            abstract: OrderRepositoryContract::class,
            concrete: OrderRepository::class
        );

        $this->app->bind(
            abstract: OrderManagerContract::class,
            concrete: OrderManager::class,
        );

        $this->app->bind(
            abstract: CartReaderContract::class,
            concrete: CartReaderRepository::class,
        );
    }
}

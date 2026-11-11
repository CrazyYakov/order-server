<?php

declare(strict_types=1);

namespace Marketplace\Cart\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Marketplace\Cart\Core\Ports\CartManagerContract;
use Marketplace\Cart\Core\Ports\CartRepositoryContract;
use Marketplace\Cart\Core\Ports\ProductRepositoryContract;
use Marketplace\Cart\Core\Ports\TransactorContract;
use Marketplace\Cart\Infrastructure\Listeners\ClearCartOnOrderCreated;
use Marketplace\Cart\Infrastructure\Manager\CartManger;
use Marketplace\Cart\Infrastructure\Repositories\CartRepository;
use Marketplace\Cart\Infrastructure\Repositories\ProductRepository;
use Marketplace\Cart\Infrastructure\Services\Transactor;
use Marketplace\Order\Core\Domain\Events\OrderCreated;

class ProfileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            OrderCreated::class,
            ClearCartOnOrderCreated::class,
        );

        $this->app->bind(
            CartRepositoryContract::class,
            CartRepository::class
        );

        $this->app->bind(
            CartManagerContract::class,
            CartManger::class
        );

        $this->app->bind(
            ProductRepositoryContract::class,
            ProductRepository::class
        );

        $this->app->bind(
            TransactorContract::class,
            Transactor::class,
        );
    }
}

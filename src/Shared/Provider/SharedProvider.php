<?php

namespace Marketplace\Shared\Provider;

use Illuminate\Support\ServiceProvider;
use Marketplace\Shared\Core\Ports\DomainEventDispatcherContract;
use Marketplace\Shared\Infrastructure\Adapters\EventDispatcher;

class SharedProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(
            DomainEventDispatcherContract::class,
            EventDispatcher::class,
        );
    }
}

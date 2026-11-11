<?php

namespace Marketplace\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Marketplace\Shared\Core\Ports\TransactionContract;
use Marketplace\Shared\Infrastructure\Adapters\Transaction;

class SharedProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(
            TransactionContract::class,
            Transaction::class,
        );
    }
}

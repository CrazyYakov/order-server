<?php

return [
    Marketplace\Shared\Infrastructure\Providers\SharedProvider::class,
    Marketplace\Auth\Infrastructure\Providers\AuthServiceProvider::class,
    Marketplace\Product\Infrastructure\Providers\ProductServiceProvider::class,
    Marketplace\Order\Infrastructure\Providers\OrderServiceProvider::class,
    Marketplace\Cart\Infrastructure\Providers\ProfileServiceProvider::class,
    Marketplace\Payment\Infrastructure\Providers\PaymentServiceProvider::class,
];

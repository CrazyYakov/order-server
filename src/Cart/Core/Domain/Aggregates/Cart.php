<?php

declare(strict_types=1);

namespace Marketplace\Cart\Core\Domain\Aggregates;

use Marketplace\Cart\Core\Domain\Contracts\ProductCollection;

readonly class Cart
{
    public function __construct(
        public ProductCollection $productCollection,
        public int $userId,
    ) {}
}

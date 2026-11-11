<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Domain\Values;

final readonly class OrderItem
{
    public function __construct(
        public int $productId,
        public float $price,
        public int $count,
    ) {}
}

<?php

declare(strict_types=1);

namespace Marketplace\Cart\Core\Domain\Entities;

readonly class Product
{
    public function __construct(
        public int   $id,
        public float $price,
        public int $count,
    ) {}
}

<?php

namespace Marketplace\Cart\Core\Ports;

use Marketplace\Cart\Core\Domain\Entities\Product;

interface ProductRepositoryContract
{
    public function getById(int $id): ?Product;
}

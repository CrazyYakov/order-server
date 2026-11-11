<?php

namespace Marketplace\Cart\Core\Ports;

use Marketplace\Cart\Core\Domain\Aggregates\Cart;

interface CartRepositoryContract
{
    public function getCartByUser(int $userId): ?Cart;
}

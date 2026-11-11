<?php

namespace Marketplace\Cart\Core\Application\Exception;

use Exception;
use Override;

class InsufficientStockException extends Exception
{
    #[Override]
    public function __construct(int $productId)
    {
        return parent::__construct(
            'Product [' . $productId . ']' . 'insufficient stock. '
        );
    }
}

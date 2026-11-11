<?php

namespace Marketplace\Cart\Core\Domain\Contracts;

use Marketplace\Cart\Core\Domain\Entities\CartProduct;
use Traversable;

/**
 * @implements Traversable<array-key, CartProduct>
 */
interface ProductCollection extends Traversable {}

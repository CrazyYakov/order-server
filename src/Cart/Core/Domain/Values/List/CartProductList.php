<?php

declare(strict_types=1);

namespace Marketplace\Cart\Core\Domain\Values\List;

use Countable;
use IteratorAggregate;
use Marketplace\Cart\Core\Domain\Contracts\ProductCollection;
use Marketplace\Cart\Core\Domain\Entities\CartProduct;
use Traversable;

/**
 * @implements IteratorAggregate<int, CartProduct>
 */
final readonly class CartProductList implements ProductCollection, IteratorAggregate, Countable
{
    /** @param array<int, CartProduct> $products */
    public function __construct(
        private array $products,
    ) {}

    /**
     * @return Traversable<int, CartProduct>
     */
    public function getIterator(): Traversable
    {
        yield from $this->products;
    }

    public function count(): int
    {
        return count($this->products);
    }
}

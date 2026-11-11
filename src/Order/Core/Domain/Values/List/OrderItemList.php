<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Domain\Values\List;

use Countable;
use Iterator;
use Marketplace\Order\Core\Domain\Values\OrderItem;

/**
 * @implements Iterator<int, OrderItem>
 */
final class OrderItemList implements Iterator, Countable
{
    /** @var array<int, OrderItem> */
    private array $list;

    private int $position;

    /** @param array<int, OrderItem> $list */
    public function __construct(array $list)
    {
        $this->list = array_values($list);
        $this->position = 0;
    }

    /**
     * @inheritDoc
     */
    public function current(): OrderItem
    {
        return $this->list[$this->position];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->list);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->list);
    }
}

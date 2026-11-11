<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Domain\Events;

use Marketplace\Shared\Core\Domain\Contracts\DomainEventContract;

final readonly class OrderCreated implements DomainEventContract
{
    public function __construct(
        public int $orderId,
        public int $userId,
    ) {}
}

<?php

namespace Marketplace\Auth\Core\Domain\Events;

use Marketplace\Shared\Core\Domain\Contracts\DomainEventContract;

final readonly class UserRegistered implements DomainEventContract
{
    public function __construct(
        public int $userId,
    ) {}
}

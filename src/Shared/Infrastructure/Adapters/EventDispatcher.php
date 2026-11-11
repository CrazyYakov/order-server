<?php

namespace Marketplace\Shared\Infrastructure\Adapters;

use Marketplace\Shared\Core\Domain\Contracts\DomainEventContract;
use Marketplace\Shared\Core\Ports\DomainEventDispatcherContract;

class EventDispatcher implements DomainEventDispatcherContract
{
    public function dispatch(DomainEventContract $event): void
    {
        event($event);
    }
}

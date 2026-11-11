<?php

namespace Marketplace\Shared\Core\Ports;

use Marketplace\Shared\Core\Domain\Contracts\DomainEventContract;

interface DomainEventDispatcherContract
{
    public function dispatch(DomainEventContract $event): void;
}

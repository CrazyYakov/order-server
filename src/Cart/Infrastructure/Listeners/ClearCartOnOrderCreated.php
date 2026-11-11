<?php

declare(strict_types=1);

namespace Marketplace\Cart\Infrastructure\Listeners;

use Marketplace\Cart\Core\Application\Service\CartService;
use Marketplace\Order\Core\Domain\Events\OrderCreated;

final readonly class ClearCartOnOrderCreated
{
    public function __construct(
        private CartService $cartService,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $this->cartService->deleteOldCartAndCreateNew($event->userId);
    }
}

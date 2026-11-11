<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Application\Actions;

use Marketplace\Order\Core\Application\Exceptions\EmptyCartException;
use Marketplace\Order\Core\Application\Exceptions\OrderExistException;
use Marketplace\Order\Core\Domain\Events\OrderCreated;
use Marketplace\Order\Core\Domain\Values\Enums\OrderStatusEnum;
use Marketplace\Order\Core\Ports\CartReaderContract;
use Marketplace\Order\Core\Ports\OrderManagerContract;
use Marketplace\Order\Core\Ports\OrderRepositoryContract;
use Marketplace\Shared\Core\Ports\DomainEventDispatcherContract;

final readonly class CreateOrderAction
{
    public function __construct(
        private OrderManagerContract $orderManager,
        private OrderRepositoryContract $orderRepository,
        private CartReaderContract $cartReader,
        private DomainEventDispatcherContract $eventDispatcher,
    ) {}

    /**
     * @throws OrderExistException
     * @throws EmptyCartException
     */
    public function run(int $userId, int $paymentMethodId): int
    {
        $lastOrder = $this->orderRepository->getLastOrder($userId);

        if ($lastOrder?->status === OrderStatusEnum::ON_PAYMENT) {
            throw new OrderExistException('Last order is not payed');
        }

        $items = $this->cartReader->getItems($userId);

        if (count($items) === 0) {
            throw new EmptyCartException('Cart is empty');
        }

        $orderId = $this->orderManager->create($userId, $paymentMethodId, $items);

        $this->orderManager->generateUrl($orderId);

        $this->eventDispatcher->dispatch(new OrderCreated($orderId, $userId));

        return $orderId;
    }
}

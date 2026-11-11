<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Ports;

use DateTimeInterface;
use Marketplace\Order\Core\Domain\Values\Enums\OrderStatusEnum;
use Marketplace\Order\Core\Domain\Values\List\OrderItemList;

interface OrderManagerContract
{
    public function create(int $userId, int $paymentMethodId, OrderItemList $items): int;

    public function generateUrl(int $orderId): void;

    public function updateStatus(int $orderId, OrderStatusEnum $status): void;

    public function cancelExpiredOrders(DateTimeInterface $date): void;
}

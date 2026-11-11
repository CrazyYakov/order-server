<?php

declare(strict_types=1);

namespace Marketplace\Order\Core\Ports;

use Marketplace\Order\Core\Domain\Values\List\OrderItemList;

interface CartReaderContract
{
    public function getItems(int $userId): OrderItemList;
}

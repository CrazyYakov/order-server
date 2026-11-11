<?php

declare(strict_types=1);

namespace Marketplace\Order\Infrastructure\Repositories;

use App\Models\User;
use Marketplace\Order\Core\Domain\Values\List\OrderItemList;
use Marketplace\Order\Core\Domain\Values\OrderItem;
use Marketplace\Order\Core\Ports\CartReaderContract;

final readonly class CartReaderRepository implements CartReaderContract
{
    public function getItems(int $userId): OrderItemList
    {
        $cart = User::query()->find($userId)?->cart;

        if ($cart === null) {
            return new OrderItemList([]);
        }

        $items = [];

        foreach ($cart->products as $product) {
            $productInCart = $product->productInCart;

            assert($productInCart !== null);

            $items[] = new OrderItem(
                (int) $product->getKey(),
                $productInCart->price,
                $productInCart->count,
            );
        }

        return new OrderItemList($items);
    }
}

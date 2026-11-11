<?php

namespace Marketplace\Cart\Infrastructure\Repositories;

use App\Models\Product;
use App\Models\User;
use Marketplace\Cart\Core\Domain\Aggregates\Cart;
use Marketplace\Cart\Core\Domain\Entities\CartProduct;
use Marketplace\Cart\Core\Domain\Values\List\CartProductList;
use Marketplace\Cart\Core\Ports\CartRepositoryContract;

class CartRepository implements CartRepositoryContract
{
    public function getCartByUser(int $userId): ?Cart
    {
        $cartModel = User::find($userId)
            ?->cart
            ->load('products');

        if ($cartModel === null) {
            return null;
        }

        $productList = $cartModel->products
            ->map(function (Product $product) {
                return new CartProduct(
                    $product->id,
                    $product->productInCart->price,
                    $product->productInCart->count,
                    $product->title,
                );
            });

        return new Cart(
            new CartProductList($productList->all()),
            $userId
        );
    }
}

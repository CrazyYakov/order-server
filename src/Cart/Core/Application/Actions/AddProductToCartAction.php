<?php

declare(strict_types=1);

namespace Marketplace\Cart\Core\Application\Actions;

use Marketplace\Cart\Core\Application\Exception\CartNotFoundException;
use Marketplace\Cart\Core\Application\Exception\InsufficientStockException;
use Marketplace\Cart\Core\Application\Exception\ProductNotFoundException;
use Marketplace\Cart\Core\Ports\ProductRepositoryContract;
use Marketplace\Cart\Core\Ports\CartManagerContract;
use Marketplace\Cart\Core\Ports\CartRepositoryContract;
use Marketplace\Shared\Core\Ports\TransactionContract;
use Throwable;

final readonly class AddProductToCartAction
{
    public function __construct(
        private CartManagerContract $cartManager,
        private CartRepositoryContract $cartRepository,
        private ProductRepositoryContract $productRepository,
        private TransactionContract $transaction,
    ) {}

    /**
     * @throws ProductNotFoundException
     * @throws CartNotFoundException
     * @throws InsufficientStockException
     */
    public function run(int $userId, int $productId, int $count = 1): void
    {
        try {
            $this->transaction->begin();

            $product = $this->productRepository->getById($productId);

            if ($product === null) {
                throw new ProductNotFoundException($productId);
            }

            if ($product->count < $count) {
                throw new InsufficientStockException($product->id);
            }

            $cart = $this->cartRepository->getCartByUser($userId);

            if ($cart === null) {
                throw new CartNotFoundException();
            }

            $this->cartManager->storeProductInCart($cart, $product);

            $this->transaction->commit();
        } catch (Throwable $e) {
            $this->transaction->rollback();

            throw $e;
        }
    }
}

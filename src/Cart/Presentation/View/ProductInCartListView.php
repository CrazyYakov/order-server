<?php

declare(strict_types=1);

namespace Marketplace\Cart\Presentation\View;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Marketplace\Cart\Core\Domain\Entities\CartProduct;

/**
 * @implements Arrayable<string, mixed>
 */
class ProductInCartListView implements Arrayable
{
    public function __construct(
        private iterable $productInCartList
    ) {}

    public function toArray(): array
    {
        return Arr::map(iterator_to_array($this->productInCartList), function (CartProduct $productCart) {
            return [
                'id' => $productCart->id,
                'title' => $productCart->title,
                'price' => $productCart->price,
                'count' => $productCart->count,
            ];
        });
    }
}

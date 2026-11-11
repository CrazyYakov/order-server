<?php

declare(strict_types=1);

namespace Marketplace\Cart\Presentation\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Marketplace\Cart\Core\Ports\CartRepositoryContract;
use Marketplace\Shared\Presentation\Responses\SuccessResponse;
use Marketplace\Cart\Presentation\View\ProductInCartListView;
use Marketplace\Shared\Presentation\Responses\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Cart')]
class IndexProductCartController
{
    public function __construct(
        private CartRepositoryContract $cartRepository,
    ) {}

    #[OA\Get(
        path: '/api/cart',
        operationId: 'getUserCart',
        tags: ['Cart'],
        summary: 'Получение содержимого корзины пользователя',
        description: 'Возвращает список товаров в корзине текущего авторизованного пользователя.',
        security: [
            ['sanctum' => []],
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Содержимое корзины',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', description: 'ID товара', example: 1),
                                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15'),
                                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 999.99),
                                    new OA\Property(property: 'count', type: 'integer', description: 'Количество в корзине', example: 2),
                                ],
                            ),
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ],
                ),
            ),
        ],
    )]
    public function __invoke(Request $request): Responsable
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        $cart = $this->cartRepository->getCartByUser($user->id);

        return new SuccessResponse(
            new ProductInCartListView($cart->productCollection)
        );
    }
}

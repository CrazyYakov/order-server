<?php

declare(strict_types=1);

namespace Marketplace\Cart\Presentation\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Marketplace\Cart\Core\Application\Actions\AddProductToCartAction;
use Marketplace\Cart\Core\Application\Exception\CartNotFoundException;
use Marketplace\Cart\Core\Application\Exception\InsufficientStockException;
use Marketplace\Cart\Core\Application\Exception\ProductNotFoundException;
use Marketplace\Cart\Presentation\Response\SuccessResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Cart')]
class StoreProductCartController
{
    public function __construct(
        private AddProductToCartAction $action
    ) {
    }

    #[OA\Post(
        path: '/api/carts/{id}',
        operationId: 'addProductToCart',
        tags: ['Cart'],
        summary: 'Добавление товара в корзину',
        description: 'Добавляет товар по его ID в корзину текущего авторизованного пользователя.',
        security: [
            ['sanctum' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID добавляемого товара',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Товар добавлен в корзину',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object', nullable: true),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Товар с указанным ID не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\Models\Product] 1'),
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
    public function __invoke(Request $request, int $id): Responsable
    {
        try {
            $user = $request->user();

            $this->action->run($user->id, $id);
        } catch (ProductNotFoundException $e) {
            abort(404, $e->getMessage());
        } catch (CartNotFoundException $e) {
            abort(500, $e->getMessage());
        } catch (InsufficientStockException $e) {
            abort(422, $e->getMessage());
        }

        return new SuccessResponse();
    }
}

<?php

declare(strict_types=1);

namespace Marketplace\Order\Presentation\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Marketplace\Auth\Presentation\Response\SuccessResponse;
use Marketplace\Order\Core\Ports\OrderRepositoryContract;
use Marketplace\Order\Presentation\View\OrderView;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Order')]
readonly class ShowOrderController
{
    public function __construct(
        private OrderRepositoryContract $orderRepository,
    ) {}

    #[OA\Get(
        path: '/api/orders/{id}',
        operationId: 'getOrder',
        tags: ['Order'],
        summary: 'Получение заказа по ID',
        description: 'Возвращает заказ по его идентификатору с перечнем зарезервированных товаров.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID заказа',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Заказ найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', description: 'ID заказа', example: 1),
                                new OA\Property(property: 'full_price', type: 'number', format: 'float', description: 'Итоговая стоимость заказа', example: 1999.98),
                                new OA\Property(property: 'count', type: 'integer', description: 'Общее количество товаров в заказе', example: 2),
                                new OA\Property(
                                    property: 'products',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'string', description: 'ID товара', example: '1'),
                                            new OA\Property(property: 'title', type: 'string', example: 'iPhone 15'),
                                            new OA\Property(property: 'price', type: 'number', format: 'float', description: 'Цена из резерва', example: 999.99),
                                            new OA\Property(property: 'quantity', type: 'integer', description: 'Количество в заказе', example: 2),
                                        ],
                                    ),
                                ),
                            ],
                        ),
                        new OA\Property(property: 'message', type: 'string', example: 'order'),
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Заказ с указанным ID не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\Models\Order] 1'),
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
    public function __invoke(int $id): Responsable
    {
        return new SuccessResponse(
            new OrderView($this->orderRepository->getById($id))
        );
    }
}

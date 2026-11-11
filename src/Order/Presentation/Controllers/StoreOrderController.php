<?php

declare(strict_types=1);

namespace Marketplace\Order\Presentation\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Marketplace\Order\Core\Application\Actions\CreateOrderAction;
use Marketplace\Order\Core\Application\Exceptions\EmptyCartException;
use Marketplace\Order\Core\Application\Exceptions\OrderExistException;
use Marketplace\Order\Presentation\Response\SuccessResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Order')]
final readonly class StoreOrderController
{
    public function __construct(
        private CreateOrderAction $createOrderAction,
    ) {}

    #[OA\Post(
        path: '/api/orders/pay/{paymentMethodId}',
        operationId: 'createOrder',
        tags: ['Order'],
        summary: 'Создание заказа из корзины',
        description: 'Создаёт заказ из содержимого корзины пользователя, генерирует платёжную ссылку выбранного способа оплаты и очищает корзину.',
        security: [
            ['sanctum' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'paymentMethodId',
                description: 'ID способа оплаты',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Заказ создан и отправлен на оплату',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object', nullable: true),
                    ],
                ),
            ),
            new OA\Response(
                response: 400,
                description: 'Предыдущий заказ не оплачен или корзина пуста',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Last order is not payed'),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Способ оплаты не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\Models\PaymentMethod] 1'),
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
    public function __invoke(Request $request, int $paymentMethodId): Responsable
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        try {
            $this->createOrderAction->run($user->id, $paymentMethodId);
        } catch (OrderExistException|EmptyCartException $e) {
            abort(400, $e->getMessage());
        }

        return new SuccessResponse();
    }
}

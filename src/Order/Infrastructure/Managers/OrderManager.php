<?php

declare(strict_types=1);

namespace Marketplace\Order\Infrastructure\Managers;

use App\Models\Order as OrderModel;
use App\Models\PaymentMethod;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Marketplace\Order\Core\Domain\Values\Enums\OrderStatusEnum;
use Marketplace\Order\Core\Domain\Values\List\OrderItemList;
use Marketplace\Order\Core\Ports\OrderManagerContract;

class OrderManager implements OrderManagerContract
{
    public function create(int $userId, int $paymentMethodId, OrderItemList $items): int
    {
        return DB::transaction(function () use ($userId, $paymentMethodId, $items): int {
            $order = new OrderModel();
            $order->status = OrderStatusEnum::ON_PAYMENT;
            $order->user_id = $userId;
            $order->payment_method_id = $paymentMethodId;
            $order->save();

            $this->syncOrderProducts($order, $items);

            return (int) $order->getKey();
        });
    }

    public function generateUrl(int $orderId): void
    {
        $orderModel = OrderModel::findOrFail($orderId);

        $orderModel->url = PaymentMethod::findOrFail($orderModel->payment_method_id)
            ->getClass()
            ->generateUrlForOrder($orderId);

        $orderModel->save();
    }

    private function syncOrderProducts(OrderModel $order, OrderItemList $items): void
    {
        $products = [];

        foreach ($items as $item) {
            $products[$item->productId] = [
                'price' => $item->price,
                'count' => $item->count,
            ];
        }

        $order->products()->sync($products);
    }

    public function updateStatus(int $orderId, OrderStatusEnum $status): void
    {
        $order = OrderModel::query()->findOrFail($orderId);
        $order->status = $status;
        $order->save();
    }

    public function cancelExpiredOrders(DateTimeInterface $date): void
    {
        OrderModel::query()
            ->where('created_at', "<", $date)
            ->where('status', OrderStatusEnum::ON_PAYMENT)
            ->update([
                'status' => OrderStatusEnum::CANCELED,
            ]);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Order\Presentation\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use Database\Seeders\PaymentMethodSeeder;
use Marketplace\Order\Core\Domain\Values\Enums\OrderStatusEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StoreOrderControllerTest extends TestCase
{
    #[DataProvider('dataPaymentMethodProvider')]
    public function testCreatesOrderFromCart(string $paymentMethod): void
    {
        $this->seed(PaymentMethodSeeder::class);

        $product = $this->getRandomProduct();
        $user = $this->getRandomUser();
        $cart = $user->cart;

        $cart->products()->attach($product, [
            'price' => $product->price,
            'count' => 2,
        ]);

        $paymentMethodId = PaymentMethod::where('slug', $paymentMethod)
            ->firstOrFail()
            ->getKey();

        $this->withToken($user->createToken('test_api')->plainTextToken)
            ->postJson('/api/orders/pay/' . $paymentMethodId)
            ->assertOk();

        $order = Order::query()
            ->where('user_id', $user->getKey())
            ->firstOrFail();

        $this->assertSame(OrderStatusEnum::ON_PAYMENT, $order->status);
        $this->assertNotSame('', $order->url);

        $this->assertDatabaseHas('orders_products', [
            'order_id' => $order->getKey(),
            'product_id' => $product->getKey(),
            'price' => $product->price,
            'count' => 2,
        ]);

        $this->assertDatabaseMissing('carts', [
            'id' => $cart->getKey(),
        ]);
    }

    public function testReturnsBadRequestWhenLastOrderIsNotPayed(): void
    {
        $this->seed(PaymentMethodSeeder::class);

        $product = $this->getRandomProduct();
        $user = $this->getRandomUser();

        $user->cart->products()->attach($product, [
            'price' => $product->price,
            'count' => 1,
        ]);

        $paymentMethodId = PaymentMethod::where('slug', 'alpha_bank')
            ->firstOrFail()
            ->getKey();

        $token = $user->createToken('test_api')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/orders/pay/' . $paymentMethodId)
            ->assertOk();

        $this->withToken($token)
            ->postJson('/api/orders/pay/' . $paymentMethodId)
            ->assertStatus(400);
    }

    public function testReturnsBadRequestWhenCartIsEmpty(): void
    {
        $this->seed(PaymentMethodSeeder::class);

        $user = $this->getRandomUser();

        $paymentMethodId = PaymentMethod::where('slug', 'alpha_bank')
            ->firstOrFail()
            ->getKey();

        $this->withToken($user->createToken('test_api')->plainTextToken)
            ->postJson('/api/orders/pay/' . $paymentMethodId)
            ->assertStatus(400);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->getKey(),
        ]);
    }

    public function testUnauthorizedWithoutToken(): void
    {
        $this->seed(PaymentMethodSeeder::class);

        $paymentMethodId = PaymentMethod::where('slug', 'alpha_bank')
            ->firstOrFail()
            ->getKey();

        $this->postJson('/api/orders/pay/' . $paymentMethodId)
            ->assertUnauthorized();
    }

    /** @return array<int, list<string>> */
    public static function dataPaymentMethodProvider(): array
    {
        return [
            ['alpha_bank'],
            ['beta_bank'],
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $payload = [
            'product_id' => $product->id,
            'quantity' => 1
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $payload);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'order_id',
                'previous_orders' => [
                    '*' => [
                        'id',
                        'product_id',
                        'product' => [
                            'name',
                            'price',
                        ],
                        'quantity',
                        'price',
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_guest_cannot_create_order()
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(401);
    }

    public function test_user_can_view_own_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);


        $response = $this->actingAs($user)->getJson("/api/orders/{$order->id}");

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                [
                    'id',
                    'product_id',
                    'user_id',
                    'quantity',
                    'price',
                    'status',
                    'created_at',
                    'updated_at',
                    'product' => [
                        'id',
                        'name',
                        'price',
                        'description',
                        'stock',
                        'created_at',
                        'updated_at',
                    ],
                ]
            ],
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'status' => 'new',
        ]);


    }

    public function test_user_cannot_view_others_order()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'product_id' => $product->id,
            'user_id' => $user1->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);


        $response = $this->actingAs($user2)->getJson("/api/orders/{$order->id}");

        $response->assertStatus(404);
    }
}

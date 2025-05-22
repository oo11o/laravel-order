<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        foreach (Product::all() as $product) {
            Order::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'quantity' => rand(1, 5),
                'price' => $product->price * rand(1, 5),
                'status' => 'new',
            ]);
        }
    }
}

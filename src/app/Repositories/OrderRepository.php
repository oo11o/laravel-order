<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function findByUserWithProduct(int $userId): Collection
    {
        return Order::with('product')
            ->where('user_id', $userId)
            ->get();
    }

    public function findByOrderIdWithProduct(int $orderId, int $userId): ?Order
    {
        return Order::with('product')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }
}

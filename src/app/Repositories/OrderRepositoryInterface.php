<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function findByUserWithProduct(int $userId): Collection;
    public function findByOrderIdWithProduct(int $orderId, int $userId): ?Order;
}

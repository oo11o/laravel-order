<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

interface OrderServiceInterface
{
    public function create(array $data, User $user): Order;
    public function getPreviousProductNamesForUser(User $user): ?Collection;
    public function getOrder(int $orderId, int $userId): ?Order;
}

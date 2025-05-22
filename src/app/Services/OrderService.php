<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;

use App\Exceptions\OrderCreateFailedException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\OrderNotFoundException;

use Illuminate\Database\Eloquent\Collection;


class OrderService implements OrderServiceInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected OrderRepositoryInterface  $orderRepository,
    )
    {
    }

    public function create(array $data, User $user): Order
    {
        $product = $this->productRepository->findById($data['product_id']);
        if (!$product) {
            throw new ProductNotFoundException();
        }

        try {
            $order = new Order();
            $order->product_id = $product->id;
            $order->user_id = $user->id;
            $order->quantity = $data['quantity'];
            $order->price = $product->price * $data['quantity'];
            $order->status = 'new';
            $order->save();
            return $order;
        } catch (\Exception $e) {
            throw new OrderCreateFailedException();
        }

        event(new OrderCreated($order));

        return $order;
    }

    public function getPreviousProductNamesForUser(User $user): ?Collection
    {
        return $this->orderRepository->findByUserWithProduct($user->id);
    }

    public function getOrder(int $orderId, int $userId): Order
    {

        $order = $this->orderRepository->findByOrderIdWithProduct($orderId, $userId);
        if (!$order) {
            throw new OrderNotFoundException();
        }
        return $order;
    }
}

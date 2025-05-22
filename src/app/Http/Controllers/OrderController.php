<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderNotFoundException;
use App\Services\OrderServiceInterface;

use App\Enums\SuccessCodeEnum;
use App\Enums\ErrorCodeEnum;

use App\Http\Requests\StoreOrderRequest;

use App\Exceptions\OrderCreateFailedException;
use App\Exceptions\ProductNotFoundException;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

use App\Http\Resources\OrderResource;


class OrderController extends Controller
{
    public function __construct(
        protected OrderServiceInterface $orderService,
    )
    {
    }

    public function store(StoreOrderRequest $request)
    {
        $user = auth()->user();

        try {
            $order = $this->orderService->create($request->validated(), $user);
            $previousOrders = $this->orderService->getPreviousProductNamesForUser($user);

            return ApiResponse::success(
                [
                    'order_id' => $order->id,
                    'previous_orders' => OrderResource::collection($previousOrders),
                ],
                SuccessCodeEnum::ORDER_CREATED->message());

        } catch (ProductNotFoundException $e) {
            return ApiResponse::error(ErrorCodeEnum::PRODUCT_NOT_FOUND->message(), 404, ErrorCodeEnum::PRODUCT_NOT_FOUND->value);
        } catch (OrderCreateFailedException $e) {
            return ApiResponse::error(ErrorCodeEnum::ORDER_CREATED_FAILED->message(), 500, ErrorCodeEnum::ORDER_CREATED_FAILED->value);
        } catch (\Exception $e) {
            Log::error(ErrorCodeEnum::UNKNOWN_ERROR->message(), [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return ApiResponse::error(ErrorCodeEnum::UNKNOWN_ERROR->message(), 500, ErrorCodeEnum::UNKNOWN_ERROR->value);
        }
    }

    public function show($orderId)
    {

        $validator = validator(
            ['order' => $orderId],
            ['order' => ['required', 'integer']],
        );

        if ($validator->fails()) {
            return ApiResponse::error(
                ErrorCodeEnum::VALIDATION_ERROR->message(),
                422,
                $validator->errors()->toArray());
        }

        try {
            $order = $this->orderService->getOrder($orderId, auth()->id());

            return ApiResponse::success(
                [
                    OrderResource::make($order),
                ],
                SuccessCodeEnum::ORDER_FOUND->message());
        } catch (OrderNotFoundException $e) {
            return ApiResponse::error(ErrorCodeEnum::ORDER_NOT_FOUND->message(), 404, ErrorCodeEnum::ORDER_CREATED_FAILED->value);
        } catch (\Exception $e) {
            Log::error(ErrorCodeEnum::UNKNOWN_ERROR->message(), [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return ApiResponse::error(ErrorCodeEnum::UNKNOWN_ERROR->message(), 500, ErrorCodeEnum::UNKNOWN_ERROR->value);
        }
    }
}

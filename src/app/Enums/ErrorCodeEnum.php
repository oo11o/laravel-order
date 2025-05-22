<?php

namespace App\Enums;

enum ErrorCodeEnum: int
{
    case PRODUCT_NOT_FOUND = 1001;
    case QUANTITY_INVALID = 1002;
    case VALIDATION_ERROR = 1003;
    case UNKNOWN_ERROR = 1004;
    case ORDER_CREATED_FAILED = 1005;
    case FORBIDDEN = 1006;
    case ORDER_NOT_FOUND = 1007;

    public function message(): string
    {
        return match ($this) {
            self::PRODUCT_NOT_FOUND => 'Product not found.',
            self::QUANTITY_INVALID => 'Invalid quantity.',
            self::VALIDATION_ERROR => 'Validation error.',
            self::UNKNOWN_ERROR => 'Undocumented error.',
            self::ORDER_CREATED_FAILED => 'Order create failed.',
            self::ORDER_NOT_FOUND  => 'Order not found.',
            self::FORBIDDEN => 'Forbidden.',
        };
    }
}

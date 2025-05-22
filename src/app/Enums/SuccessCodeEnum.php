<?php

namespace App\Enums;

enum SuccessCodeEnum: int
{
    case ORDER_CREATED = 2001;
    case ORDER_FOUND = 2002;

    public function message(): string
    {
        return match ($this) {
            self::ORDER_CREATED => 'Order created successfully',
            self::ORDER_FOUND => 'Order retrieved successfully',
        };
    }
}

<?php

namespace App\Enum;

enum GatewayType: int
{
    case Production = 1;
    case Testing = 2;

    public function toString(): string
    {
        return match($this) {
            self::Production => trans('enum.transaction.gateway.type.production'),
            self::Testing => trans('enum.transaction.gateway.type.testing'),
        };
    }
}

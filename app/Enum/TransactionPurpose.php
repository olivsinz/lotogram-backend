<?php

namespace App\Enum;

enum TransactionPurpose: int
{
    case In = 1;
    case Out = 2;

    public function toString(): string
    {
        return match($this) {
            self::In => trans('enum.transaction.purpose.in'),
            self::Out => trans('enum.transaction.purpose.out')
        };
    }
}

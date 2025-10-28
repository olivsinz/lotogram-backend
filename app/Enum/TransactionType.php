<?php

namespace App\Enum;

enum TransactionType: int
{
    case Method = 1;
    case Competition = 2;
    case Bonus = 3;

    public function toString(): string
    {
        return match($this) {
            self::Method => trans('enum.transaction.type.method'),
            self::Competition => trans('enum.transaction.type.competition'),
            self::Bonus => trans('enum.transaction.type.bonus')
        };
    }
}

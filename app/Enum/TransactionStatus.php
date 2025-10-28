<?php

namespace App\Enum;

enum TransactionStatus: int
{
    case Pending = 1;
    case Processing = 2;
    case Reviewing = 3;
    case Completed = 4;
    case Canceled = 9;

    public function toString(): string
    {
        return match($this) {
            self::Completed => trans('enum.transaction.status.completed'),
            self::Canceled => trans('enum.transaction.status.canceled'),
            self::Pending => trans('enum.transaction.status.pending'),
            self::Processing => trans('enum.transaction.status.processing'),
            self::Reviewing => trans('enum.transaction.status.reviewing')
        };
    }
}

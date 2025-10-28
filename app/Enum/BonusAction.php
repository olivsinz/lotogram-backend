<?php

namespace App\Enum;

enum BonusAction: int
{
    case Welcome = 1;

    public function toString(): string
    {
        return match($this) {
            self::Welcome => trans('enum.bonus.action.welcome'),
        };
    }
}

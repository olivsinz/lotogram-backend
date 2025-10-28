<?php

namespace App\Enum;

enum UserType: int
{
    case User = 1;
    case Bot = 2;
    case Admin = 3;
    case Owner = 4;

    public function toString(): string
    {
        return match($this) {
            self::User => trans('enum.user.type.user'),
            self::Bot => trans('enum.user.type.bot'),
            self::Admin => trans('enum.user.type.admin'),
            self::Owner => trans('enum.user.type.owner'),
        };
    }

    public function isVisible(): string
    {
        return match($this) {
            self::User => true,
            self::Bot => false,
            self::Admin => true,
            self::Owner => true,
        };
    }
}

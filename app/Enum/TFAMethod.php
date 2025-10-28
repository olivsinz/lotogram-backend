<?php

namespace App\Enum;

enum TFAMethod: int
{
    case None = 1;
    case Authenticator = 2;
    case Mail = 3;

    public function toString(): string
    {
        return match($this) {
            self::None => trans('enum.user.tfa_method.none'),
            self::Authenticator => trans('enum.user.tfa_method.authenticator'),
            self::Mail => trans('enum.user.tfa_method.mail'),
        };
    }
}

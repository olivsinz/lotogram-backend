<?php

namespace App\Enum;

enum MethodType: int
{
    case VirtualWallet = 1;
    case BankTransfer = 2;
    case Crypto = 3;

    public function toString(): string
    {
        return match($this) {
            self::VirtualWallet => trans('enum.method.type.virtual_wallet'),
            self::BankTransfer => trans('enum.method.type.bank_transfer'),
            self::Crypto => trans('enum.method.type.crypto')
        };
    }
}

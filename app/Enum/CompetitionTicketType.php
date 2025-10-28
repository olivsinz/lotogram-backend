<?php

namespace App\Enum;

enum CompetitionTicketType: int
{
    case User = 1;
    case Bot = 2;

    public function toString(): string
    {
        return match($this) {
            self::User => trans('enum.competition-ticket.type.user'),
            self::Bot => trans('enum.competition-ticket.type.bot'),
        };
    }

    public function isVisible(): string
    {
        return match($this) {
            self::User => true,
            self::Bot => false,
        };
    }
}

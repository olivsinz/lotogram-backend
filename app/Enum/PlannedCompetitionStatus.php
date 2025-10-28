<?php

namespace App\Enum;

enum PlannedCompetitionStatus: int
{
    case Passive = 1;
    case Active = 2;


    public function toString(): string
    {
        return match($this) {
            self::Passive => trans('enum.planned-competition.status.passive'),
            self::Active => trans('enum.planned-competition.status.active')
        };
    }
}

<?php

namespace App\Enum;

enum CompetitionStatus: int
{
    case Passive = 1;
    case Preparing = 2;
    case Ready = 3;
    case Active = 4;
    case WaitingResults = 5;
    case ResultsStarted = 6;
    case Completed = 7;
    case Canceled = 8;

    public function toString(): string
    {
        return match($this) {
            self::Passive => trans('enum.competition.status.passive'),
            self::Preparing => trans('enum.competition.status.preparing'),
            self::Ready => trans('enum.competition.status.ready'),
            self::Active => trans('enum.competition.status.active'),
            self::WaitingResults => trans('enum.competition.status.waiting_results'),
            self::ResultsStarted => trans('enum.competition.status.results_started'),
            self::Completed => trans('enum.competition.status.completed'),
            self::Canceled => trans('enum.competition.status.canceled'),
        };
    }
}

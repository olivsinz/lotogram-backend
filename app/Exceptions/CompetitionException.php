<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompetitionException extends Exception
{
    public function __construct($message = "", protected $errorKey = 'competition_failed')
    {
        parent::__construct($message);
    }

    public function report()
    {
        // return false;
    }


    public function render(Request $request): Response
    {
        return response()->error([
            'key' => $this->errorKey,
            'message' => $this->getMessage(),
        ], 422);
    }

    public static function alreadyCreated()
    {
        return new static(__('exception.competition.already_created'));
    }

    public static function totalPercentageExceeded()
    {
        return new static(__('exception.competition.rewards_total_percentage_exceeded'));
    }
}

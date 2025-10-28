<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WithdrawException extends Exception
{
    public function __construct($message = "", protected $errorKey = 'withdraw_failed')
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

    public static function missedProfileInfo()
    {
        return new static(__('exception.withdraw.invalid_profile_info'), 'withdraw_failed.missing_profile_info');
    }


}

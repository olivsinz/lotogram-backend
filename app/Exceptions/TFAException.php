<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TFAException extends Exception
{
    public function report()
    {
        // return false;
    }

    public function render(Request $request): Response
    {
        return response()->error([
            'key' => 'tfa_failed',
            'message' => $this->getMessage(),
        ], 422);
    }

    public static function alreadyExists()
    {
        return new static(__('auth.tfa_already_exists'));
    }

    public static function emailNotVerified()
    {
        return new static(__('auth.tfa_email_not_verified'));
    }

    public static function invalidCode()
    {
        return new static(__('auth.tfa_invalid_code'));
    }

    public static function codeAlreadySent()
    {
        return new static(__('auth.tfa_code_already_sent'));
    }

    public static function notExists()
    {
        return new static(__('auth.tfa_not_exists'));
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorizationException extends Exception
{
    public function __construct($message = "", protected $errorKey = 'authorization_failed')
    {
        parent::__construct($message);
        $this->errorKey = $errorKey;
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
        ], 401);
    }

    public static function invalidCredentials()
    {
        return new static(__('auth.invalid_credentials'));
    }

    public static function invalidTfaCode()
    {
        return new static(__('auth.invalid_tfa_code'));
    }

    public static function invalidTfaMethod()
    {
        return new static(__('auth.invalid_tfa_method'));
    }

    public static function notActiveUser()
    {
        return new static(__('auth.not_active_user'));
    }

    public static function requiresTFA()
    {
        return new static(__('auth.required_tfa'), 'requires_tfa');
    }

    public static function unauthorizedIpAddress()
    {
        return new static(__('auth.unauthorized_ip_address'));
    }
}

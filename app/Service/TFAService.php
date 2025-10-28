<?php

namespace App\Service;

use App\Models\User;
use App\Enum\TFAMethod;
use App\Mail\ConfirmationCode;
use App\Exceptions\TFAException;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class TFAService
{
    public static function isEnablePossible(User $user, $method, $secret): bool|TFAException
    {
        if ($user->has_tfa)
        {
            return throw TFAException::alreadyExists();
        }

        if ($method == TFAMethod::Mail->value && $user->email_verified_at == null)
        {
            return throw TFAException::emailNotVerified();
        }

        if ($method == TFAMethod::Authenticator->value && !(new Google2FA)->verifyKey($user->tfa_secret, $secret))
        {
            return throw TFAException::invalidCode();
        }

        if ($method == TFAMethod::Mail->value && $secret != Cache::get('tfa_code_enable_tfa_model_users_'.$user->id))
        {
            return throw TFAException::invalidCode();
        }

        return true;
    }

    public static function isDisablePossible(User $user, $secret): bool|TFAException
    {
        if (!$user->has_tfa)
        {
            return throw TFAException::notExists();
        }

        if ($user->tfa_method == TFAMethod::Authenticator->value && !(new Google2FA)->verifyKey($user->tfa_secret, $secret))
        {
            return throw TFAException::invalidCode();
        }

        if ($user->tfa_method == TFAMethod::Mail->value && $secret != Cache::get('tfa_code_disable_tfa_model_users_'.$user->id))
        {
            return throw TFAException::invalidCode();
        }

        return true;
    }

    public static function getQRCodeUrl(User $user, $fresh = false): string
    {
        if ($fresh || $user->tfa_secret == null)
        {
            $user->update([
                'tfa_secret' => (new Google2FA)->generateSecretKey()
            ]);
        }

        return (new Google2FA)->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->tfa_secret
        );
    }

    public static function sendCode(User $user, string $purpose)
    {
        $key = 'tfa_code_' . $purpose . '_model_users_'.$user->id;
        $secret = rand(100000, 999999);

        if (Cache::has($key))
            return throw TFAException::codeAlreadySent();

        Mail::to($user)->queue(new ConfirmationCode($user, $secret, __('mail.tfa_' . $purpose . '_message')));
        Cache::add($key, $secret, now()->addMinute(10));
    }

    public static function createTFASession(User $user, $secret)
    {
        if (!$user->has_tfa)
        {
            return throw TFAException::notExists();
        }

        if ($user->tfa_method == TFAMethod::Authenticator->value && !(new Google2FA)->verifyKey($user->tfa_secret, $secret))
        {
            return throw TFAException::invalidCode();
        }

        if ($user->tfa_method == TFAMethod::Mail->value && $secret != Cache::get('tfa_code_tfa_session_model_users_'.$user->id))
        {
            return throw TFAException::invalidCode();
        }

        Cache::add('tfa_guard_session_model_user_' . $user->id, true, now()->addMinute(5));
    }
}

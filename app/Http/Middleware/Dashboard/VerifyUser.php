<?php

namespace App\Http\Middleware\Dashboard;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyUser
{
    protected $passwordChangeRuleNotRequired = [
        'App\Http\Controllers\Dashboard\AuthController@changePassword',
    ];

    protected $emailVerifyRuleNotRequired = [
        'App\Http\Controllers\Dashboard\MeController@setEmailVerified',
        'App\Http\Controllers\Dashboard\MeController@show',
        'App\Http\Controllers\Dashboard\MeController@update',
        'App\Http\Controllers\Dashboard\MeController@sendMailForVerificationCode',

    ];

    protected $tfaVerifyRuleNotRequired = [
        'App\Http\Controllers\Dashboard\AuthController@loginWithTfa',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user->tokenCan('ip_address:'. $request->ip())) {
            abort(403, __('str.session_could_not_be_verified'));
        }

        if (!$user->is_active) {
            abort(403, __('str.user_is_not_active'));
        }

        if ($user->tokenCan('tfa_required:true')) {
            if (array_search($request->route()->getActionName(), $this->tfaVerifyRuleNotRequired) === false)
                abort(403, __('str.required_two_factor_authentication'));
        }

        if ($user->password_change_required) {
            if (array_search($request->route()->getActionName(), $this->passwordChangeRuleNotRequired) === false)
                abort(403, __('str.password_reset_required'));
        }

        if (!$user->email_verified_at) {
            if (array_search($request->route()->getActionName(), $this->emailVerifyRuleNotRequired) === false)
                abort(403, __('str.unconfirmed_mail_address'));
        }

        return $next($request);
    }
}

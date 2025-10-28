<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Enum\TFAMethod;
use App\Service\AuthService;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Mail\ConfirmationCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserInterfaceSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Exceptions\AuthorizationException;
use App\Http\Requests\Dashboard\Auth\LoginRequest;
use App\Http\Resources\Dashboard\Auth\LoginResource;
use App\Http\Requests\Dashboard\Auth\RegisterRequest;
use App\Http\Requests\Dashboard\Auth\ForgotPasswordRequest;
use App\Http\Requests\Dashboard\Auth\LoginTfaVerifyRequest;
use App\Http\Requests\Dashboard\Auth\ForgotPasswordVerifyRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::guard('web')->attempt($request->payload()))
            throw AuthorizationException::invalidCredentials();

        $user = Auth::user();

        if (!AuthService::checkLoginableIpAddresses($user))
            throw AuthorizationException::unauthorizedIpAddress();

        if (!$user->is_active)
            throw AuthorizationException::notActiveUser();

        $tokenAbilities = AuthService::createAbilities($user);

        ($user->has_tfa && $user->tfa_method == TFAMethod::Mail->value)
            && Mail::to($user)->queue(new ConfirmationCode($user, AuthService::$secret, trans('mail.login_confirmation_message')));

        $user->tokens()->delete();
        $token = $user->createToken($request->header('User-Agent'), $tokenAbilities, AuthService::$expiresAt);

        $user = AuthService::tokenablePayload($user, $token->plainTextToken);
        $user->setAttribute('setting', UserInterfaceSetting::filterByUserId($user->id)->first());

        $user->load(['permissions', 'roles']);

        return new LoginResource($user, $user->has_tfa);
    }

    public function loginWithTfa(LoginTfaVerifyRequest $request)
    {
        $user = Auth::user();

        if (!AuthService::checkLoginableIpAddresses($user))
            throw AuthorizationException::unauthorizedIpAddress();

        if (!$user->is_active)
            throw AuthorizationException::notActiveUser();

        if (AuthService::checkTFA($user, $request->secret)) {
            $tokenAbilities = AuthService::createAbilities(Auth::user(), true);
            $user->tokens()->delete();
            $token = $user->createToken($request->header('User-Agent'), $tokenAbilities, AuthService::$expiresAt);

            $user = AuthService::tokenablePayload($user, $token->plainTextToken);
            $user->setAttribute('setting', UserInterfaceSetting::filterByUserId($user->id)->first());

            return new LoginResource($user);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return Response::noContent();
    }

    public function register (RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create($request->payload());
            $setting = UserInterfaceSetting::firstOrCreate(['user_id' => $user->id,],['setting' => '{}',]);
            $tokenAbilities = AuthService::createAbilities($user);
            $token = $user->createToken($request->header('User-Agent'), $tokenAbilities, AuthService::$expiresAt);
            $user = AuthService::tokenablePayload($user, $token->plainTextToken);
            $user->setAttribute('setting', $setting);
            UserService::sendEmailVerificationCode($user, $token->plainTextToken);

            /*
            if (config('app.env') == 'local' || config('app.env') == 'test')
                \App\Service\TransactionService::newTransaction($user, 1000000, 0, 1000000, \App\Enum\TransactionPurpose::In, \App\Enum\TransactionStatus::Completed, \App\Enum\TransactionType::Method, \App\Models\Method::inRandomOrder()->first());
            */

            return $user;
        });

        Auth::login($user);

        return new LoginResource($user);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user)
            UserService::sendResetPasswordCode($user);

        return Response::noContent();
    }

    public function setForgotPasswordVerified(ForgotPasswordVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user)
            UserService::setResetPasswordVerified($user, $request->validated());

        return Response::noContent();
    }
}

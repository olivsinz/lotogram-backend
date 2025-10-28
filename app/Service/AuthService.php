<?php

namespace App\Service;

use App\Models\User;
use App\Enum\TFAMethod;
use App\Service\UserService;
use Illuminate\Support\Carbon;
use PragmaRX\Google2FA\Google2FA;
use App\Exceptions\AuthorizationException;

class AuthService
{
    public static $secret = null;
    public static $expiresAt = null;

    public static function secret(int $length = 6): int
    {
        return rand(10 ** ($length - 1), (10 ** $length) - 1);
    }

    public static function createAbilities(User $user, $checkTfa = false)
    {
        if ($user->has_tfa && !$checkTfa)
        {
            self::$secret = self::secret(6);
            self::$expiresAt = Carbon::now()->addMinutes(2);

            return [
                'tfa_required:true',
                'tfa_method:' . $user->tfa_method,
                'tfa_secret:' . self::$secret,
                'ip_address:' . request()->ip()
            ];
        }

        self::$expiresAt = Carbon::now()->addMinutes(config('app.token_life_time'));

        return [
            'ip_address:' . request()->ip()
        ];
    }

    public static function getSecurityScore(User $user): array
    {
        $score = 0;
        $messages = [];

        if ($user->has_tfa == false)
        {
            $score += 50;
            $messages[] = trans('security.messages.tfa_not_enabled');
        }

        if ($user->password_change_required)
        {
            $score += 20;
            $messages[] = trans('security.messages.password_change_required');
        }

        if ($user->email_verified_at == null)
        {
            $score += 10;
            $messages[] = trans('security.messages.email_not_verified');
        }

        if (Carbon::parse($user->password_changed_at)->lt(Carbon::now()->subMonths(3)))
        {
            $score += 20;
            $messages[] = trans('security.messages.password_not_changed');
        }

        return [
            'score' => $score,
            'messages' => $messages
        ];
    }

    public static function checkTFA (User $user, $secret): AuthorizationException|bool
    {
        if ($user->tfa_method == TFAMethod::Mail->value && !$user->tokenCan('tfa_secret:' . $secret)) {
            return throw AuthorizationException::invalidTfaCode();
        }

        if ($user->tfa_method == TFAMethod::Authenticator->value && !(new Google2FA())->verifyKey((string) $user->tfa_secret, (string) $secret)) {
            return throw AuthorizationException::invalidTfaCode();
        }

        return true;
    }

    public static function tokenablePayload(User $user, $token): User
    {
        $user->setAttribute('api_token', $token);
        $user->setAttribute('api_token_create_time', time());
        $user->setAttribute('api_token_life_time', config('app.token_life_time'));
        $user->setAttribute('security', self::getSecurityScore($user));

        return $user;
    }

    public static function checkLoginableIpAddresses(User $user, ?string $ipAddress= null): bool
    {
        $ipAddress = $ipAddress ?? request()->ip();

        if ($user->ipAddresses()->count() > 0) {
            return $user->ipAddresses()->filterByIpAddress($ipAddress)->exists();
        }

        return true;
    }
}

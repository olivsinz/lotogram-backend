<?php

namespace App\Service;

use App\Enum\UserType;
use App\Events\EmailVerified;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use App\Models\Method;
use App\Models\Permission;
use App\Mail\ConfirmationCode;
use App\Models\UserInterfaceSetting;
use App\Models\UserIpWhitelist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService
{
    public static function isOwner(User $user = null): bool
    {
        $user = $user !== null ? $user : Auth::user();
        return in_array($user->email, config('app.owners')) && $user->type === UserType::Owner->value;
    }

    public static function assignRole(User $user, Role $role)
    {
        if (!(self::isOwner() || Auth::user()->hasRole($role->name))){
            return throw new AccessDeniedHttpException;
        }

        if ($user->hasRole($role->name)) {
            return throw new ConflictHttpException;
        }

        $user->assignRole($role);
    }

    public static function revokeRole(User $user, Role $role)
    {
        if (!(self::isOwner() || Auth::user()->hasRole($role->name))){
            return throw new AccessDeniedHttpException;
        }

        if (!$user->hasRole($role->name)) {
            return throw new NotFoundHttpException;
        }

        $user->removeRole($role);
    }

    public static function assignPermission(User $user, array $permissionUuid): void
    {
        if (!in_array($user->email, config('app.owners')))
        {
            $userPermissionId = Auth::user()->roles->pluck('id')->toArray();
            $assignablePermissions = Role::whereIn('uuid', $permissionUuid)->whereIn('id', $userPermissionId)->get();
        }

        $assignablePermissions = Permission::whereIn('uuid', $permissionUuid)->get();
        foreach($assignablePermissions as $permission)
        {
            $user->givePermissionTo($permission);
        }
    }

    public static function revokePermission(User $user, array $roleUuid): void
    {
        $assignablePermissions = Permission::whereIn('uuid', $roleUuid)->get();
        foreach($assignablePermissions as $role)
        {
            $user->revokePermissionTo($role);
        }
    }

    public static function assignMethod(User $user, Method $method)
    {
        $isExists = $user->methods()->exists($method);

        if($isExists){
            return throw new ConflictHttpException;
        }

        $user->methods()->attach($method);
    }

    public static function revokeMethod(User $user, Method $method)
    {
        $isExists = $user->methods()->exists($method);

        if(!$isExists){
            return throw new NotFoundHttpException;
        }

        $user->methods()->detach($method);
    }

    public static function assignSite (User $user, Site $site)
    {
        $isExists = $user->sites()->where('site_id', $site->id)->exists();

        if ($isExists) {
            return throw new ConflictHttpException;
        }

        return $user->sites()->attach($site->id);
    }

    public static function revokeSite(User $user, Site $site)
    {
        $isExists = $user->sites()->where('site_id', $site->id)->exists();

        if (!$isExists) {
            return throw new NotFoundHttpException;
        }

        return $user->sites()->detach($site->id);
    }

    public static function createOrUpdateUserInterfaceSetting(User $user, $setting = [])
    {
        $isExists = UserInterfaceSetting::where('user_id', $user->id)->first();

        if ($isExists) {
            $isExists->update(['setting' => $setting]);
            return;
        }

        $user->userInterfaceSetting()->create(['setting' => $setting]);
    }

    public static function sendEmailVerificationCode(User $user, $token)
    {
        $code = rand(100000, 999999);

        if (Cache::has('email_verify_code_model_users_' . $user->id))
            return throw new ConflictHttpException;

        $message = '<a href="' . config('app.app_ui_url') . '/email-verification?email='.$user->email.'&code='. $code .'&api_token='.$token.'">';
        $message .= trans('mail.mail_verify_link');
        $message .= '</a>';

        Mail::to($user)->queue(new ConfirmationCode($user, $code, $message));
        Cache::put('email_verify_code_model_users_' . $user->id, $code, now()->addMinutes(1));
    }

    public static function setEmailVerified(User $user, string $secret)
    {
        if (Cache::get('email_verify_code_model_users_' . $user->id) != $secret)
            return throw new NotFoundHttpException;

        $user->update(['email_verified_at' => now()]);

        event(new EmailVerified(Auth::user()));
    }

    public static function assignIpAddress (User $user, $ipAddress)
    {
        $isExists = $user->ipAddresses()->where('ip_address', $ipAddress)->exists();

        if ($isExists) {
            return throw new ConflictHttpException;
        }

        return $user->ipAddresses()->create(['ip_address' => $ipAddress]);
    }

    public static function revokeIpAddress(User $user, UserIpWhitelist $ipAddress)
    {
        $isExists = $user->ipAddresses()->where('id', $ipAddress->id)->exists();

        if (!$isExists) {
            return throw new NotFoundHttpException;
        }

        return $user->ipAddresses()->where('id', $ipAddress->id)->delete();
    }

    public static function sendResetPasswordCode(User $user)
    {
        $code = rand(100000, 999999);

        if (Cache::has('reset_password_code_model_users_' . $user->id))
            return throw new ConflictHttpException;

        $content = trans('mail.reset_password_code_message');
        $content .= '<br /> <br />';
        $content .= '<a href="'.config('app.app_ui_url').'/forgot-password/verify?code='.$code.'&mail='.$user->email.'">' . trans('mail.password_reset_link') . '</a>';

        Mail::to($user)->queue(new ConfirmationCode($user, $code, $content));
        Cache::put('reset_password_code_model_users_' . $user->id, $code, now()->addMinutes(1));
    }

    public static function setResetPasswordVerified(User $user, $payload)
    {
        $payload = (object) $payload;

        if (Cache::get('reset_password_code_model_users_' . $user->id) != $payload->confirmation_code)
            return throw new NotFoundHttpException;

        Cache::forget('reset_password_code_model_users_' . $user->id);

        $user->update([
            'password' => $payload->password,
            'password_changed_at' => now()
        ]);
    }
}

<?php

namespace App\Http\Requests\Dashboard\Auth;

use App\Rules\StringRule;
use App\Enum\UserLanguage;
use App\Enum\UserType;
use App\Rules\GoogleRecaptcha;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', StringRule::defaults(), 'max:100'],
            'last_name' => ['required', StringRule::defaults(), 'max:100'],
            'username' => ['required', StringRule::username(), 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', /*Password::defaults(),*/ 'confirmed'],
            'g_recaptcha_response' => ['required', new GoogleRecaptcha],
        ];
    }

    public function payload ()
    {
        return [
            ...$this->only('first_name', 'last_name', 'username', 'email', 'password'),
            'email_verified_at' => null,
            'password_change_required' => false,
            'password_changed_at' => now(),
            'is_active' => true,
            'language' => UserLanguage::TR->value,
            'type' => UserType::User->value,
        ];
    }
}

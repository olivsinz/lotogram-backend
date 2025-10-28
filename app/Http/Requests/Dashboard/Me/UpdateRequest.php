<?php

namespace App\Http\Requests\Dashboard\Me;

use App\Rules\StringRule;
use App\Enum\UserLanguage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Rules\UserInterfaceSettingRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['sometimes', StringRule::defaults(), 'max:60'],
            'first_name' => ['sometimes', StringRule::defaults(), 'max:60',],
            'last_name' => ['sometimes', StringRule::defaults(), 'max:60',],
            'email' => ['sometimes', StringRule::defaults(), 'email', 'max:255', 'unique:users,email,' . Auth::id() . ',id'],
            'password' => ['sometimes', Password::defaults(), 'confirmed'],
            'settings' => ['sometimes', new UserInterfaceSettingRule, 'max:3000'],
            'language' => ['sometimes', 'integer', Rule::enum(UserLanguage::class)],
            'phone' => ['sometimes', StringRule::phone(), 'min:10', 'max:15'],
            'birth_date' => ['sometimes', 'date:Y-m-d'],
            'national_id' => ['sometimes', StringRule::nationalId(), 'digits:11'],

        ];
    }

    public function attributes()
    {
        return [
            'username' => trans('validation.attributes.username'),
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
            'email' => trans('validation.attributes.email'),
            'password' => trans('validation.attributes.password'),
            'settings' => trans('validation.attributes.settings'),
            'language' => trans('validation.attributes.language'),
        ];
    }
}

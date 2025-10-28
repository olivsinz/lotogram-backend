<?php

namespace App\Http\Requests\Dashboard\User;

use App\Enum\UserLanguage;
use App\Enum\UserType;
use App\Models\Title;
use App\Models\UserGroup;
use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use App\Rules\UserInterfaceSettingRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('user.store');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', StringRule::defaults(), 'max:60'],
            'last_name' => ['required', StringRule::defaults(), 'max:60'],
            'username' => ['required', StringRule::defaults(), 'max:60', 'unique:users,username,' . auth()->user()->group_id . ',group_id'],
            'email' => ['required', StringRule::defaults(), 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'title.id' => ['required', 'uuid', 'exists:titles,uuid,is_active,1'],
            'language' => ['required', 'integer', Rule::enum(UserLanguage::class)],
            'settings' => ['nullable', new UserInterfaceSettingRule, 'max:3000'],
            'group.id' => ['nullable', 'uuid', 'exists:user_groups,uuid,is_active,1']
        ];
    }

    public function payload()
    {
        return [
            ...$this->only('first_name', 'last_name', 'username', 'email', 'password', 'settings'),
            'title_id' => Title::uuid($this->input('title.id'))->id,
            'group_id' => auth()->user()->can('user.add-user-group') && $this->filled('group.id')
                ? UserGroup::uuid($this->input('group.id'))->id
                : auth()->user()->group_id,
            'type' => UserType::Admin->value,
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
            'username' => trans('validation.attributes.username'),
            'email' => trans('validation.attributes.email'),
            'password' => trans('validation.attributes.password'),
            'title.id' => trans('validation.attributes.title'),
            'group.id' => trans('validation.attributes.user_group'),
            'settings' => trans('validation.attributes.settings'),
            'language' => trans('validation.attributes.language')
        ];
    }
}

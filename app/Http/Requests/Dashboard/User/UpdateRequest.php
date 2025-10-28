<?php

namespace App\Http\Requests\Dashboard\User;

use App\Models\Title;
use App\Models\UserGroup;
use App\Rules\StringRule;
use App\Enum\UserLanguage;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('user.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid'],
            'username' => ['sometimes', StringRule::defaults(), 'max:60'],
            'first_name' => ['sometimes', StringRule::defaults(), 'max:60',],
            'last_name' => ['sometimes', StringRule::defaults(), 'max:60',],
            'email' => ['sometimes', StringRule::defaults(), 'email', 'max:255'],
            'password' => ['sometimes', Password::defaults(), 'confirmed'],
            'language' => ['sometimes', 'integer', Rule::enum(UserLanguage::class)],
            'title.id' => ['sometimes', 'uuid', 'exists:titles,uuid,is_active,1'],
            'group.id' => ['sometimes', 'uuid', 'exists:user_groups,uuid,is_active,1'],
        ];
    }

    public function payload(): array
    {
        $data = [];

        if ($this->has('title.id'))
            $data['title_id'] = Title::uuid($this->input('title.id'))->id;

        if ($this->has('group.id'))
            $data['group_id'] = UserGroup::uuid($this->input('group.id'))->id;

        return [
            ...$this->validated(),
            ...$data,
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.user'),
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

<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()?->can('deposit_server.store');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:deposit_servers,uuid,deleted_at,NULL'],
            'name' => ['sometimes', StringRule::defaults(), 'max:255', Rule::unique('deposit_servers', 'name')->ignore($this->uuid, 'uuid')],
            'is_active' => ['sometimes', 'boolean'],
            'form_domain' => ['sometimes', 'url', 'max:255', 'unique:deposit_servers,form_domain'],
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => __('validation.attributes.deposit_server'),
            'name' => __('validation.attributes.name'),
            'is_active' => __('validation.attributes.is_active'),
            'form_domain' => __('validation.attributes.form_domain'),
        ];
    }
}

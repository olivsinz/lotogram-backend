<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Rules\StringRule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return $this->user()->can('role.update');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:roles,uuid,deleted_at,NULL'],
            'name' => ['sometimes', StringRule::defaults(), 'max:255', 'unique:roles,name,' . $this->uuid . ',uuid'],
            'description' => ['nullable', StringRule::defaults(), 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => __('validation.attributes.role'),
            'name' => __('validation.attributes.name'),
            'description' => __('validation.attributes.description'),
            'is_active' => __('validation.attributes.is_active'),
        ];
    }
}

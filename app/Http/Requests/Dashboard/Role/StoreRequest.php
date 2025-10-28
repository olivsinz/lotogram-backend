<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('role.store');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', StringRule::defaults(), 'max:255', 'unique:roles,name'],
            'description' => ['nullable', StringRule::defaults(), 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('validation.attributes.name'),
            'description' => __('validation.attributes.description'),
            'is_active' => __('validation.attributes.is_active'),
        ];
    }
}

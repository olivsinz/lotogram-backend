<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('role.index');
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', StringRule::defaults(), 'max:60',],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function passedValidation (): void
    {
        $this->replace([
            'per_page' => $this->filled('per_page') ? $this->per_page : 10,
        ]);
    }

    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.name'),
            'is_active' => __('validation.attributes.is_active'),
            'per_page' => __('validation.attributes.per_page'),
            'page' => __('validation.attributes.page'),
        ];
    }
}

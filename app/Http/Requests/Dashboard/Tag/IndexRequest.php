<?php

namespace App\Http\Requests\Dashboard\Tag;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('tag.index');
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', StringRule::defaults(), 'max:60'],
            'color' => ['nullable', StringRule::defaults(), 'max:255'],
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

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.tag'),
            'is_active' => trans('validation.attributes.is_active'),
            'color' => trans('validation.attributes.color'),
            'page' => trans('validation.attributes.page'),
            'per_page' => trans('validation.attributes.per_page')
        ];
    }
}

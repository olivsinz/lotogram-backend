<?php

namespace App\Http\Requests\Dashboard\Tag;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('tag.store');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', StringRule::defaults(), 'max:60', 'unique:tags,name'],
            'description' => ['nullable', StringRule::defaults(), 'max:255'],
            'is_active' => ['required', 'boolean'],
            'color' => ['required', 'string', 'min:4', 'max:7', 'regex:/^#[a-f0-9]{6}$/i'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('validation.attributes.name'),
            'description' => trans('validation.attributes.description'),
            'is_active' => trans('validation.attributes.is_active'),
            'color' => trans('validation.attributes.color')
        ];
    }
}

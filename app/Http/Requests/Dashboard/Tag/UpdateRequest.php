<?php

namespace App\Http\Requests\Dashboard\Tag;

use App\Rules\StringRule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('tag.update');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:tags,uuid,deleted_at,NULL'],
            'description' => ['nullable', StringRule::defaults(), 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'color' => ['sometimes', 'string', 'min:4', 'max:7', 'regex:/^#[a-f0-9]{6}$/i'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.tag'),
            'description' => trans('validation.attributes.description'),
            'is_active' => trans('validation.attributes.is_active'),
            'color' => trans('validation.attributes.color')
        ];
    }
}

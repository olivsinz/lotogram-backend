<?php

namespace App\Http\Requests\Dashboard\User;

use App\Models\Title;
use App\Rules\StringRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return auth()->user()->can('user.index') && auth()->user()->can('required-tfa');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', StringRule::defaults(), 'max:60',],
            'last_name' => ['nullable', StringRule::defaults(), 'max:60',],
            'email' => ['nullable', StringRule::defaults(), 'max:60',],
            'title_id' => ['nullable', 'uuid', 'exists:titles,uuid'],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function passedValidation (): void
    {
        $this->replace([
            'title_id' => $this->filled('title_id') ? Title::uuid($this->title_id)->id : null,
            'per_page' => $this->filled('per_page') ? $this->per_page : 10,
        ]);
    }

    public function attributes(): array
    {
        return [
            'first_name' => __('validation.attributes.first_name'),
            'last_name' => __('validation.attributes.last_name'),
            'email' => __('validation.attributes.email'),
            'title_id' => __('validation.attributes.title_id'),
            'is_active' => __('validation.attributes.is_active'),
        ];
    }
}

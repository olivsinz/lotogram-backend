<?php

namespace App\Http\Requests\Dashboard\Me;

use Illuminate\Foundation\Http\FormRequest;

class DisableTFARequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'secret' => ['nullable', 'integer', 'digits:6'],
        ];
    }

    public function attributes(): array
    {
        return [
            'secret' => trans('validation.attributes.secret'),
        ];
    }
}

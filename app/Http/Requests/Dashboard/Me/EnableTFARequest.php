<?php

namespace App\Http\Requests\Dashboard\Me;

use App\Enum\TFAMethod;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EnableTFARequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', Rule::enum(TFAMethod::class)],
            'secret' => ['required', 'numeric', 'digits:6'],
        ];
    }

    public function attributes(): array
    {
        return [
            'method' => trans('validation.attributes.method.tfa'),
            'secret' => trans('validation.attributes.secret'),
        ];
    }
}

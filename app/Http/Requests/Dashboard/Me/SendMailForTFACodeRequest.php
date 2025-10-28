<?php

namespace App\Http\Requests\Dashboard\Me;

use Illuminate\Foundation\Http\FormRequest;

class SendMailForTFACodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purpose' => ['required', 'in:disable_tfa,enable_tfa']
        ];
    }

    public function attributes(): array
    {
        return [
            'secret' => trans('validation.attributes.purpose.tfa'),
        ];
    }
}

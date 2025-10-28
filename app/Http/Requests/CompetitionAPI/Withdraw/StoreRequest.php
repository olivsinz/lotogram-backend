<?php

namespace App\Http\Requests\CompetitionAPI\Withdraw;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', StringRule::defaults(), 'max:255', 'exists:methods,slug'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'account' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'slug' => __('validation.attributes.slug'),
            'amount' => __('validation.attributes.amount'),
            'account' => __('validation.attributes.account'),
        ];
    }
}

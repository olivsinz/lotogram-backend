<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('deposit_server.store');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', StringRule::defaults(), 'max:255', 'unique:deposit_servers,name'],
            'is_active' => ['required', 'boolean'],
            'form_domain' => ['required', 'url', 'max:255', 'unique:deposit_servers,form_domain'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.name'),
            'is_active' => __('validation.attributes.is_active'),
            'form_domain' => __('validation.attributes.form_domain'),
        ];
    }
}

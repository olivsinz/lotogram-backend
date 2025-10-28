<?php

namespace App\Http\Requests\Dashboard\Method;

use App\Enum\MethodType;
use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('method.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', StringRule::defaults(), 'max:255', 'unique:methods,name'],
            'is_active' => ['required', 'boolean'],
            'deposit_status' => ['required', 'boolean'],
            'withdraw_status' => ['required', 'boolean'],
            'worker_status' => ['required', 'boolean'],
            'slug' => ['required', 'unique:methods,slug', StringRule::defaults(), 'max:255'],
            'type' => ['required', Rule::enum(MethodType::class)],
            'panel_domain' => ['required', 'url', 'max:255'],
        ];
    }
}

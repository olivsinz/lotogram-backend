<?php

namespace App\Http\Requests\Dashboard\Method;

use App\Enum\MethodType;
use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('method.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:methods,uuid'],
            'name' => ['sometimes', StringRule::defaults(), 'max:255', Rule::unique('methods', 'name')->ignore($this->uuid, 'uuid')],
            'is_active' => ['sometimes', 'boolean'],
            'deposit_status' => ['sometimes', 'boolean'],
            'withdraw_status' => ['sometimes', 'boolean'],
            'worker_status' => ['sometimes', 'boolean'],
            'slug' => ['sometimes', Rule::unique('methods', 'slug')->ignore($this->uuid, 'uuid'), StringRule::defaults(), 'max:255'],
            'type' => ['sometimes', Rule::enum(MethodType::class)],
            'panel_domain' => ['sometimes', 'url', 'max:255'],
        ];
    }
}

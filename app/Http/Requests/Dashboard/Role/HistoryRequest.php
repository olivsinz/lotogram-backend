<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class HistoryRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('role.history');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:roles,uuid,deleted_at,NULL'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function passedValidation (): void
    {
        $this->replace([
            'per_page' => $this->filled('per_page') ? $this->per_page : 10,
        ]);
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.role'),
            'per_page' => __('validation.attributes.per_page'),
            'page' => __('validation.attributes.page'),
        ];
    }
}

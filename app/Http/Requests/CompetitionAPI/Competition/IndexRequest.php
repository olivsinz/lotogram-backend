<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Rules\StringRule;
use App\Enum\CompetitionStatus;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::enum(CompetitionStatus::class)],
        ];
    }

    public function passedValidation (): void
    {
        $this->merge([
            'per_page' => $this->filled('per_page') ? $this->per_page : 20,
        ]);
    }

    public function attributes(): array
    {
        return [
            'is_active' => __('validation.attributes.is_active'),
            'per_page' => __('validation.attributes.per_page'),
            'page' => __('validation.attributes.page'),
        ];
    }
}

<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class HistoryRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()?->can('deposit_server.history');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:deposit_servers,uuid,is_active,1,deleted_at,NULL'],
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
            'uuid' => trans('validation.attributes.deposit_server'),
        ];
    }
}

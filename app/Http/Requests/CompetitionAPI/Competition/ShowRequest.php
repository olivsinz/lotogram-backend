<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use Illuminate\Support\Facades\Auth;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:competitions,uuid,deleted_at,NULL'],
            'number' => ['sometimes', 'integer', 'digits_between:1,10']
        ];
    }

    public function passedValidation()
    {
        if ($this->filled('number')) {
            $this->replace([
                'number' => implode('-', str_split($this->number))
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.competitions')
        ];
    }
}

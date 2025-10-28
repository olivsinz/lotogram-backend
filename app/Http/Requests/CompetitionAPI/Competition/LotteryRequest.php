<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class LotteryRequest extends FormRequest
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
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.competitions')
        ];
    }
}

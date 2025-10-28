<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('deposit_server.destroy');
    }

        public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:deposit_servers,uuid,deleted_at,NULL'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.deposit_server'),
        ];
    }
}

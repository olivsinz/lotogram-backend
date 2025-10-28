<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('role.show');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:roles,uuid,deleted_at,NULL'],
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.role')
        ];
    }
}

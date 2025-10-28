<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignMethodRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.assign-method');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,is_active,1,deleted_at,NULL'],
            'method.id' => ['uuid', 'exists:methods,uuid,is_active,1,deleted_at,NULL'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.user'),
            'method.id' => trans('validation.attributes.method.method'),
        ];
    }
}

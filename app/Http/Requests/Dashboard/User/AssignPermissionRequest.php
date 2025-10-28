<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.assign-permission');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,is_active,1'],
            'permission_id' => ['required', 'array', 'min:1'],
            'permission_id.*' => ['uuid', 'exists:permissions,uuid'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.user'),
            'permission_id' => trans('validation.attributes.permission'),
            'permission_id.*' => trans('validation.attributes.permission'),
        ];
    }
}

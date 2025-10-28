<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class RevokePermissionRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('role.revoke-permission');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:roles,uuid,is_active,1,deleted_at,NULL'],
            'permission_uuid' => ['required', 'uuid', 'exists:permissions,uuid'],
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.role'),
            'permission_uuid' => trans('validation.attributes.permission_uuid')
        ];
    }

}

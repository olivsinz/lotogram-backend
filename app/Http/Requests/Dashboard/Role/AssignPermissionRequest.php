<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('role.assign-permission');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:roles,uuid,is_active,1,deleted_at,NULL'],
            'permission.id' => ['required', 'uuid', 'exists:permissions,uuid']
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.role'),
            'permission.id' => trans('validation.attributes.permission_uuid')
        ];
    }
}

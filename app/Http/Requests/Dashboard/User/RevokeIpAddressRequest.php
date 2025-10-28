<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class RevokeIpAddressRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.revoke-ip_address');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,is_active,1,deleted_at,NULL'],
            'ip_address_uuid' => ['required', 'uuid', 'exists:user_ip_whitelists,uuid,deleted_at,NULL'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.user'),
            'ip_address_uuid' => trans('validation.attributes.ip_address'),
        ];
    }
}

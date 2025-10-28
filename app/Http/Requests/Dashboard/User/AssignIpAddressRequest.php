<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignIpAddressRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.assign-ip_address');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,deleted_at,NULL'],
            'ip_address' => ['required', 'ip'], //TODO: Burada ip adresi ilgili user için zaten eklenmşi mi kontrol edilebilir.
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => __('validation.attributes.user'),
            'ip_address' => __('validation.attributes.ip_address'),
        ];
    }
}

<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class RevokeSiteRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.revoke-site');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,is_active,1,deleted_at,NULL'],
            'site_uuid' => ['required', 'uuid'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.user'),
            'site_uuid' => trans('validation.attributes.site'),
        ];
    }
}

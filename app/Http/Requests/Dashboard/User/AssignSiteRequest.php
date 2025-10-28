<?php

namespace App\Http\Requests\Dashboard\User;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignSiteRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('user.assign-site');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:users,uuid,is_active,1,deleted_at,NULL'],
            'site.id' => ['required', 'uuid', 'exists:sites,uuid,is_active,1,deleted_at,NULL'],
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.user'),
            'site.id' => trans('validation.attributes.site'),
        ];
    }
}

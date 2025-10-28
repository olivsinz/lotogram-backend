<?php

namespace App\Http\Requests\Dashboard\Tag;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('tag.destroy');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:tags,uuid,deleted_at,NULL']
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.tag'),
        ];
    }
}

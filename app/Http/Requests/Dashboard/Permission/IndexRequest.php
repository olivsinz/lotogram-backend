<?php

namespace App\Http\Requests\Dashboard\Permission;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('permission.index');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('setting.index');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}

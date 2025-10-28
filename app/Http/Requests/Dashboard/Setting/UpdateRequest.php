<?php

namespace App\Http\Requests\Dashboard\Setting;

use App\Models\Setting;
use Illuminate\Validation\Validator;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('setting.update');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:settings,uuid'],
            'value' => ['required', 'max:60'],
        ];
    }

    public function after ()
    {
        $setting = Setting::uuid($this->uuid);

        return [
            function (Validator $validator) use ($setting) {
                if (!$this->validate(['value' => $setting->type])){
                    $validator->errors()->add('value', trans('validation.boolean', ['attribute' => 'value']));
                }
            }
        ];
    }

    public function attributes()
    {
        return [
            'uuid' => trans('validation.attributes.setting'),
            'value' => trans('validation.attributes.value'),
        ];
    }
}

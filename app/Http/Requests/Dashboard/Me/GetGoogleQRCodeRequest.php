<?php

namespace App\Http\Requests\Dashboard\Me;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class GetGoogleQRCodeRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}

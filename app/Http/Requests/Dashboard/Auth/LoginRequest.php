<?php

namespace App\Http\Requests\Dashboard\Auth;

use App\Rules\StringRule;
use App\Rules\GoogleRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required_without:username', 'email', 'exists:users,email'],
            'username' => ['required_without:email', StringRule::username(), 'exists:users,username'],
            'password' => ['required'],
            'g_recaptcha_response' => ['required', new GoogleRecaptcha],
        ];
    }

    public function payload()
    {
        return $this->only('username', 'email', 'password');
    }

    protected function prepareForValidation()
    {
        if (strpos($this->username, '@') !== false)
        {
            $this->merge([
                'email' => $this->username
            ]);

            $input = $this->all();
            unset($input['username']);
            $this->replace($input);
        }
    }
}

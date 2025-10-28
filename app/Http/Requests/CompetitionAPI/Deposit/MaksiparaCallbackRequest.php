<?php

namespace App\Http\Requests\CompetitionAPI\Deposit;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;

class MaksiparaCallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $allowedIps = explode(',', config('app.maksipara_ip_addresses'));
        return in_array($this->ip(), $allowedIps);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trx' => ['required', StringRule::defaults(), 'exists:transactions,uuid'],
            'transaction_id' => ['required', StringRule::defaults()],
            'amount' => ['required', 'numeric', 'gt:0'],
            'status' => ['required', StringRule::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            'slug' => __('validation.attributes.slug'),
            'amount' => __('validation.attributes.amount'),
            'transaction_id' => __('validation.attributes.transaction_id'),
            'trx' => __('validation.attributes.trx'),
        ];
    }
}

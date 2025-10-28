<?php

namespace App\Http\Requests\CompetitionAPI\Competition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseTicketRequest extends FormRequest
{
    use MergeParams;
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
            'uuid' => ['required', 'uuid', 'exists:competitions,uuid,deleted_at,NULL'],
            'ticket.id' => 'sometimes|exists:competition_tickets,uuid,user_id,NULL,bet_at,NULL',
            'random' => 'sometimes|boolean',
            'amount' => 'required|numeric|min:1|gt:0'
        ];
    }

    public function attributes(): array
    {
        return [
            'uuid' => trans('validation.attributes.competitions')
        ];
    }
}

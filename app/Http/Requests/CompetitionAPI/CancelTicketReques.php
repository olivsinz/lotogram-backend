<?php

namespace App\Http\Requests\CompetitionAPI;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class CancelTicketReques extends FormRequest
{
    use MergeParams;

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
            'ticket_uuid' => ['required', 'uuid', 'exists:competition_tickets,uuid,user_id,' . auth()->id()],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $ticket = \App\Models\CompetitionTicket::where('uuid', $this->ticket_uuid)->first();

            if ($ticket->status === 'cancelled') {
                $validator->errors()->add('ticket_uuid', 'Bu bileti zaten iptal ettiniz.');
            }

            if ($ticket->bet_at < now()->addMinute($ticket->competition->cancellation_time_limit)) {
                $validator->errors()->add('ticket_uuid', 'Bu bileti iptal etmek için süre doldu.');
            }
        });
    }
}

<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Rules\StringRule;
use App\Enum\CompetitionStatus;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use MergeParams;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'exists:planned_competitions,uuid'],
            'title' => ['sometimes', StringRule::defaults(), 'max:255', 'unique:planned_competitions,title,' . $this->route('uuid') . ',uuid'],
            'cost_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'min_purchased_ticket_user' => ['sometimes', 'numeric', 'min:0'],
            'interval_minutes' => ['sometimes', 'numeric', 'min:0'],
            'planned_finish_at' => ['sometimes', 'date:Y-m-d H:i:s'],
            'status' => ['sometimes', Rule::enum(CompetitionStatus::class)],
            'real_time_count' => ['sometimes', 'numeric', 'min:0'],
            'ticket_count' => ['sometimes', 'numeric', 'min:0'],
            'ticket_amount' => ['sometimes', 'numeric', 'min:0'],
            'min_ticket_number' => ['sometimes', 'numeric', 'min:0'],
            'max_ticket_number' => ['sometimes', 'numeric', 'min:0'],
            'octet' => ['sometimes', 'numeric', 'min:0'],
            'manipulate_wait_secs_after_bot' => ['sometimes', 'numeric', 'min:0'],
            'manipulate_wait_secs_after_user' => ['sometimes', 'numeric', 'min:0'],
            'daily_limit' => ['sometimes', 'numeric', 'min:0'],
            'stats_daily_count' => ['sometimes', 'numeric', 'min:0'],
            'cancellation_time_limit' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}

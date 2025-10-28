<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Rules\StringRule;
use App\Enum\CompetitionStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.index');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', StringRule::defaults(), 'max:255', 'unique:planned_competitions'],
            'cost_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_purchased_ticket_user' => ['required', 'numeric', 'min:0'],
            'interval_minutes' => ['required', 'numeric', 'min:0'],
            'planned_finish_at' => ['required', 'date:Y-m-d H:i:s'],
            'status' => ['required', Rule::enum(CompetitionStatus::class)],
            'real_time_count' => ['required', 'numeric', 'min:0'],
            'ticket_count' => ['required', 'numeric', 'min:0'],
            'ticket_amount' => ['required', 'numeric', 'min:0'],
            'min_ticket_number' => ['required', 'numeric', 'min:0'],
            'max_ticket_number' => ['required', 'numeric', 'min:0'],
            'octet' => ['required', 'numeric', 'min:0'],
            'manipulate_wait_secs_after_bot' => ['required', 'numeric', 'min:0'],
            'manipulate_wait_secs_after_user' => ['required', 'numeric', 'min:0'],
            'daily_limit' => ['required', 'numeric', 'min:0'],
            'stats_daily_count' => ['required', 'numeric', 'min:0'],
            'cancellation_time_limit' => ['required', 'numeric', 'min:0'],
        ];
    }
}

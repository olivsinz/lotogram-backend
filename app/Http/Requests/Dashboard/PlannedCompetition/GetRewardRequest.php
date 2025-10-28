<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class GetRewardRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.get-reward');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:planned_competitions,uuid'],
        ];
    }
}

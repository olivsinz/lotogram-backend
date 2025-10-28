<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Enum\UserType;
use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class AssignRewardRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.assign-reward');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:planned_competitions,uuid'],
            'name' => ['required', StringRule::defaults(), 'min:3', 'max:255'],
            'type' => ['required', Rule::enum(UserType::class)],
            'percentage' => ['required', 'numeric', 'min:1', 'max:100'],
        ];
    }
}

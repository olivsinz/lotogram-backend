<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Enum\UserType;
use App\Rules\StringRule;
use Illuminate\Validation\Rule;
use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class EditRewardRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.edit-reward');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:planned_competitions,uuid'],
            'reward_uuid' => ['required', 'uuid', 'exists:planned_competition_rewards,uuid'], // FIXME: bu rewrd ilgili competition'da mı kontrol edilmeli
            'name' => ['sometimes', StringRule::defaults(), 'min:3', 'max:255'],
            'type' => ['sometimes', Rule::enum(UserType::class)],
            'percentage' => ['sometimes', 'numeric', 'min:1', 'max:100'],
        ];
    }
}

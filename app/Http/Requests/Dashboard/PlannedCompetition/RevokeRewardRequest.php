<?php

namespace App\Http\Requests\Dashboard\PlannedCompetition;

use App\Traits\FormRequest\MergeParams;
use Illuminate\Foundation\Http\FormRequest;

class RevokeRewardRequest extends FormRequest
{
    use MergeParams;

    public function authorize(): bool
    {
        return auth()->user()->can('planned-competition.edit-revoke');
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:planned_competitions,uuid'],
            'reward_uuid' => ['required', 'uuid', 'exists:planned_competition_rewards,uuid'], // FIXME: bu rewrd ilgili competition'da mı kontrol edilmeli
        ];
    }
}

<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'planned_competition' => $this->whenLoaded('plannedCompetition', new PlannedCompetitionResource($this->plannedCompetition)),
            'status' => $this->status,
            'is_settled_for_bots' => $this->is_settled_for_bots,
            'planned_finish_at' => $this->planned_finish_at,
            'bet_started_at' => $this->bet_started_at,
            'bet_finished_at' => $this->bet_finished_at,
            'result_started_at' => $this->result_started_at,
            'result_finished_at' => $this->result_finished_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

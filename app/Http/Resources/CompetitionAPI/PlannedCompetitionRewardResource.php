<?php

namespace App\Http\Resources\CompetitionAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannedCompetitionRewardResource extends JsonResource
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
            'title' => $this->title,
            'percentage' => $this->percentage,
            'type' => $this->whenNotNull($this->type),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
        ];
    }
}

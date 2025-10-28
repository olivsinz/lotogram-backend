<?php

namespace App\Http\Resources\CompetitionAPI;

use App\Models\Setting;
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
            'title' => $this->plannedCompetition->title,
            'octet' => $this->plannedCompetition->octet,
            'status' => $this->status,
            'ticket_amount' => $this->plannedCompetition->ticket_amount,
            //'total_reward_all' => $this->plannedCompetition->ticket_amount * $this->purchasedTickets->count(),
            'total_reward_amount' => (($this->plannedCompetition->ticket_amount * $this->purchasedTickets->count()) / 100) * (100 - $this->plannedCompetition->cost_percentage),
            'rewards' => $this->when($this->relationLoaded('plannedCompetition'), PlannedCompetitionRewardResource::collection($this->plannedCompetition->rewards)),
            'bet_started_at' => $this->bet_started_at,
            'bet_finished_at' => $this->bet_finished_at,
            'planned_finish_at' => $this->planned_finish_at,
            // TODO: buradaki count'lar optimize edilmeli.
            'total_tickets' => $this->whenNotNull($this->purchased_tickets_count, null),
            'total_users' => $this->total_users,
            //'available_tickets' => $this->when($this->relationLoaded('availableTickets'), CompetitionTicketResource::collection($this->availableTickets)),
            //'purchased_tickets' => $this->when($this->relationLoaded('purchasedTickets'), CompetitionTicketResource::collection($this->purchasedTickets))
        ];
    }
}

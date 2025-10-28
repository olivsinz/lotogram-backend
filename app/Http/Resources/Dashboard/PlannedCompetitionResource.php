<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannedCompetitionResource extends JsonResource
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
            "cost_percentage" => $this->whenNotNull($this->cost_percentage),
            "min_purchased_ticket_user" => $this->whenNotNull($this->min_purchased_ticket_user),
            "interval_minutes" => $this->whenNotNull($this->interval_minutes),
            "planned_finish_at" => $this->whenNotNull($this->planned_finish_at),
            "status" => $this->whenNotNull($this->status),
            "real_time_count" => $this->whenNotNull($this->real_time_count),
            "ticket_count" => $this->whenNotNull($this->ticket_count),
            "ticket_amount" => $this->whenNotNull($this->ticket_amount),
            "min_ticket_number" => $this->whenNotNull($this->min_ticket_number),
            "min_ticket_number" => $this->whenNotNull($this->min_ticket_number),
            "octet" => $this->whenNotNull($this->octet),
            "manipulate_wait_secs_after_bot" => $this->whenNotNull($this->manipulate_wait_secs_after_bot),
            "manipulate_wait_secs_after_user" => $this->whenNotNull($this->manipulate_wait_secs_after_user),
            "daily_limit" => $this->whenNotNull($this->daily_limit),
            "stats_daily_count" => $this->whenNotNull($this->stats_daily_count),
            "cancellation_time_limit" => $this->whenNotNull($this->cancellation_time_limit),
            "created_at" => $this->whenNotNull($this->created_at),
            "updated_at" => $this->whenNotNull($this->updated_at),
            "deleted_at" => $this->whenNotNull($this->deleted_at)
        ];
    }
}

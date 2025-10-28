<?php

namespace App\Http\Resources\CompetitionAPI;

use App\Http\Resources\CompetitionAPI\PlayerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionTicketResource extends JsonResource
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
            'amount' => $this->amount,
            'number' => $this->number,
            'user' => PlayerResource::make($this->whenLoaded('user')),
            'competition' => CompetitionResource::make($this->whenLoaded('competition')),
            'bet_at' => $this->bet_at,
            'won' => $this->won,
        ];
    }
}

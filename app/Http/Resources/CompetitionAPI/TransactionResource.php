<?php

namespace App\Http\Resources\CompetitionAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'status' => $this->status,
            'purpose' => $this->purpose,
            'type' => $this->type,
            'method' => $this->whenLoaded('method', fn () => [
                'id' => $this->method->uuid,
                'name' => $this->method->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}

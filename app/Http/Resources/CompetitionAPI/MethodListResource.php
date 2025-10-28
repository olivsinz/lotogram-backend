<?php

namespace App\Http\Resources\CompetitionAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MethodListResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'deposit_status' => $this->deposit_status,
            'withdraw_status' => $this->withdraw_status,
        ];
    }
}

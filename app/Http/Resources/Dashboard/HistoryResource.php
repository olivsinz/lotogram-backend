<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => json_decode($this->user),
            'action' => $this->action,
            'ip_address' => $this->ip_address,
            'original' => $this->original,
            'changes' => $this->changes,
            'created_at' => $this->created_at,
        ];
    }
}

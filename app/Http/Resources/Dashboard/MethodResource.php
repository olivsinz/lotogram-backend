<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MethodResource extends JsonResource
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
            'is_active' => $this->is_active,
            'deposit_status' => $this->deposit_status,
            'withdraw_status' => $this->withdraw_status,
            'worker_status' => $this->worker_status,
            'slug' => $this->slug,
            'type' => $this->type,
            'panel_domain' => $this->panel_domain,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

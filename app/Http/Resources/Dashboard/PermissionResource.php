<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'key' => $this->name,
            'name' => __('permissions.' . $this->name . '.name'),
            'description' => __('permissions.' . $this->name . '.description'),
        ];
    }
}

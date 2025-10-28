<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'key' => trans('notification.text.' . $this->key),
            'notifiable_id' => $this->notifiable_uuid,
            'notifiable_type' => Str::kebab(class_basename($this->notifiable_type)),
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}

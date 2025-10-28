<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'national_id' => $this->national_id,
            'birth_date' => $this->birth_date,
            'email_verified_at' => $this->email_verified_at,
            'password_changed_at' => $this->password_changed_at,
            'has_tfa' => $this->is_tfa,
            'tfa_method' => $this->tfa_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

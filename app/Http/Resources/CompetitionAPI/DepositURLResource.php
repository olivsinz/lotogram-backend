<?php

namespace App\Http\Resources\CompetitionAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositURLResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url
        ];
    }
}

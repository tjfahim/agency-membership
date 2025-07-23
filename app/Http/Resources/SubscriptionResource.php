<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at,
            'start_at_formatted' => $this->start_at_formatted,
            'end_at' => $this->end_at,
            'end_at_formatted' => $this->end_at_formatted,
            'payment_status' => $this->payment_status,
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'package' => $this->whenLoaded('package'),
        ];
    }
}

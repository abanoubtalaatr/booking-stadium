<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PitchResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'slot_type' => $this->slot_type,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'operating_start_time' => Carbon::parse($this->operating_start_time)->format('H:i'),
            'operating_end_time' => Carbon::parse($this->operating_end_time)->format('H:i'),
            'operating_days' => $this->operating_days,
            'hourly_rate_60' => $this->hourly_rate_60,
            'hourly_rate_90' => $this->hourly_rate_90,
            'capacity' => $this->capacity,
            'description' => $this->description,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieCastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->cast_id,
            'movie_id' => $this->movie_id,
            'actor_id' => $this->actor_id,
            'character_name' => $this->character_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->movie_id,
            'title' => $this->title,
            'release_date' => $this->release_date,
            'director_id' => $this->director_id,
            'synopsis' => $this->synopsis,
            'duration' => $this->duration,
            'poster_path' => $this->poster_path ? asset('storage/' . $this->poster_path) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships when requested
        if ($request->has('include')) {
            $includes = explode(',', $request->include);

            if (in_array('director', $includes)) {
                $data['director'] = $this->director ? [
                    'id' => $this->director->director_id,
                    'name' => $this->director->director_name,
                ] : null;
            }

            if (in_array('cast', $includes)) {
                $data['cast'] = $this->actors->map(function ($actor) {
                    return [
                        'id' => $actor->actor_id,
                        'name' => $actor->actor_name,
                        'character_name' => $actor->pivot->character_name,
                    ];
                });
            }

            if (in_array('genres', $includes)) {
                $data['genres'] = $this->genres->map(function ($genre) {
                    return [
                        'id' => $genre->genre_id,
                        'name' => $genre->genre_name,
                    ];
                });
            }

            if (in_array('reviews', $includes)) {
                $reviews = $this->reviews;
                $data['reviews'] = [
                    'count' => $reviews->count(),
                    'average_rating' => $reviews->avg('rating'),
                    'data' => $reviews->map(function ($review) {
                        return [
                            'id' => $review->review_id,
                            'user_id' => $review->user_id,
                            'rating' => $review->rating,
                            'comment' => $review->comment,
                            'created_at' => $review->created_at,
                        ];
                    }),
                ];
            }
        }

        return $data;
    }
}
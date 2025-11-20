<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Actor;
use App\Models\MovieCast;
use Illuminate\Database\Seeder;

class MovieCastSeeder extends Seeder
{
    public function run(): void
    {
        $castMap = [
            'The Matrix' => [
                ['name' => 'Keanu Reeves', 'character' => 'Neo'],
                ['name' => 'Laurence Fishburne', 'character' => 'Morpheus'],
                ['name' => 'Carrie-Anne Moss', 'character' => 'Trinity'],
            ],
            'Inception' => [
                ['name' => 'Leonardo DiCaprio', 'character' => 'Cobb'],
                ['name' => 'Joseph Gordon-Levitt', 'character' => 'Arthur'],
                ['name' => 'Elliot Page', 'character' => 'Ariadne'],
            ],
            'The Lord of the Rings: The Fellowship of the Ring' => [
                ['name' => 'Elijah Wood', 'character' => 'Frodo Baggins'],
                ['name' => 'Ian McKellen', 'character' => 'Gandalf'],
            ],
            'Blade Runner 2049' => [
                ['name' => 'Ryan Gosling', 'character' => 'K'],
                ['name' => 'Harrison Ford', 'character' => 'Rick Deckard'],
            ],
            'The Dark Knight' => [
                ['name' => 'Christian Bale', 'character' => 'Bruce Wayne / Batman'],
                ['name' => 'Heath Ledger', 'character' => 'Joker'],
            ],
            'Jurassic Park' => [
                ['name' => 'Sam Neill', 'character' => 'Dr. Alan Grant'],
                ['name' => 'Jeff Goldblum', 'character' => 'Dr. Ian Malcolm'],
            ],
            'The Shape of Water' => [
                ['name' => 'Sally Hawkins', 'character' => 'Elisa Esposito'],
            ],
            'The Martian' => [
                ['name' => 'Matt Damon', 'character' => 'Mark Watney'],
            ],
            'Pulp Fiction' => [
                ['name' => 'John Travolta', 'character' => 'Vincent Vega'],
                ['name' => 'Samuel L. Jackson', 'character' => 'Jules Winnfield'],
            ],
            'Gravity' => [
                ['name' => 'Sandra Bullock', 'character' => 'Ryan Stone'],
                ['name' => 'George Clooney', 'character' => 'Matt Kowalski'],
            ],
        ];

        foreach ($castMap as $movieTitle => $entries) {
            $movie = Movie::where('title', $movieTitle)->first();
            if (!$movie) {
                continue;
            }

            foreach ($entries as $entry) {
                $actor = Actor::firstOrCreate(['actor_name' => $entry['name']]);

                MovieCast::firstOrCreate([
                    'movie_id' => $movie->movie_id,
                    'actor_id' => $actor->actor_id,
                ], [
                    'character_name' => $entry['character'],
                ]);
            }
        }
    }
}
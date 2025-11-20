<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Director;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'The Matrix',
                'release_date' => '1999-03-31',
                'director' => 'James Cameron',
                'synopsis' => 'Un hacker descubre la verdadera naturaleza de la realidad.',
                'duration' => 136,
                'genres' => ['Acción', 'Ciencia ficción'],
            ],
            [
                'title' => 'Inception',
                'release_date' => '2010-07-16',
                'director' => 'Christopher Nolan',
                'synopsis' => 'Un ladrón roba secretos entrando en los sueños de las personas.',
                'duration' => 148,
                'genres' => ['Acción', 'Ciencia ficción', 'Suspenso'],
            ],
            [
                'title' => 'The Lord of the Rings: The Fellowship of the Ring',
                'release_date' => '2001-12-19',
                'director' => 'Peter Jackson',
                'synopsis' => 'La primera parte de la épica aventura hacia Mordor.',
                'duration' => 178,
                'genres' => ['Aventura', 'Fantasía'],
            ],
            [
                'title' => 'Blade Runner 2049',
                'release_date' => '2017-10-06',
                'director' => 'Denis Villeneuve',
                'synopsis' => 'Un replicante descubre un secreto que podría cambiar el mundo.',
                'duration' => 164,
                'genres' => ['Ciencia ficción', 'Drama'],
            ],
            [
                'title' => 'The Dark Knight',
                'release_date' => '2008-07-18',
                'director' => 'Christopher Nolan',
                'synopsis' => 'Batman enfrenta al Joker en una lucha por Gotham.',
                'duration' => 152,
                'genres' => ['Acción', 'Crimen', 'Suspenso'],
            ],
            [
                'title' => 'Jurassic Park',
                'release_date' => '1993-06-11',
                'director' => 'Steven Spielberg',
                'synopsis' => 'Dinosaurios clonados escapan en un parque temático.',
                'duration' => 127,
                'genres' => ['Aventura', 'Ciencia ficción'],
            ],
            [
                'title' => 'The Shape of Water',
                'release_date' => '2017-12-08',
                'director' => 'Guillermo del Toro',
                'synopsis' => 'Una historia de amor entre una mujer y una criatura anfibia.',
                'duration' => 123,
                'genres' => ['Drama', 'Fantasía', 'Romance'],
            ],
            [
                'title' => 'The Martian',
                'release_date' => '2015-10-02',
                'director' => 'Ridley Scott',
                'synopsis' => 'Un astronauta sobrevive en Marte con ingenio.',
                'duration' => 144,
                'genres' => ['Ciencia ficción', 'Aventura'],
            ],
            [
                'title' => 'Pulp Fiction',
                'release_date' => '1994-10-14',
                'director' => 'Quentin Tarantino',
                'synopsis' => 'Historias entrelazadas de crimen en Los Ángeles.',
                'duration' => 154,
                'genres' => ['Crimen', 'Drama'],
            ],
            [
                'title' => 'Gravity',
                'release_date' => '2013-10-04',
                'director' => 'Alfonso Cuarón',
                'synopsis' => 'Astronautas luchan por sobrevivir tras un accidente en el espacio.',
                'duration' => 91,
                'genres' => ['Suspenso', 'Drama'],
            ],
        ];

        foreach ($items as $item) {
            $director = Director::where('director_name', $item['director'])->first();
            $movie = Movie::firstOrCreate([
                'title' => $item['title'],
            ], [
                'release_date' => $item['release_date'],
                'director_id' => $director?->director_id,
                'synopsis' => $item['synopsis'],
                'duration' => $item['duration'],
            ]);

            $genreIds = collect($item['genres'])
                ->map(fn ($name) => Genre::where('genre_name', $name)->first()?->genre_id)
                ->filter()
                ->values()
                ->all();

            if (!empty($genreIds)) {
                $movie->genres()->syncWithoutDetaching($genreIds);
            }
        }
    }
}
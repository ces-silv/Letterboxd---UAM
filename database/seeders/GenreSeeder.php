<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Acción',
            'Aventura',
            'Drama',
            'Comedia',
            'Ciencia ficción',
            'Fantasía',
            'Suspenso',
            'Terror',
            'Romance',
            'Animación',
            'Documental',
            'Crimen',
        ];

        foreach ($names as $name) {
            Genre::firstOrCreate(['genre_name' => $name]);
        }
    }
}
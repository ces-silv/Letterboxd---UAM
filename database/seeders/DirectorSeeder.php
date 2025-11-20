<?php

namespace Database\Seeders;

use App\Models\Director;
use Illuminate\Database\Seeder;

class DirectorSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Christopher Nolan',
            'Steven Spielberg',
            'Martin Scorsese',
            'Quentin Tarantino',
            'Ridley Scott',
            'Denis Villeneuve',
            'James Cameron',
            'Peter Jackson',
            'Alfonso Cuarón',
            'Guillermo del Toro',
        ];

        foreach ($names as $name) {
            Director::firstOrCreate(['director_name' => $name]);
        }
    }
}
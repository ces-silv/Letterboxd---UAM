<?php

namespace Database\Seeders;

use App\Models\Actor;
use Illuminate\Database\Seeder;

class ActorSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Keanu Reeves',
            'Laurence Fishburne',
            'Carrie-Anne Moss',
            'Leonardo DiCaprio',
            'Joseph Gordon-Levitt',
            'Elliot Page',
            'Elijah Wood',
            'Ian McKellen',
            'Ryan Gosling',
            'Harrison Ford',
            'Christian Bale',
            'Heath Ledger',
            'Sam Neill',
            'Jeff Goldblum',
            'Sally Hawkins',
            'Matt Damon',
            'John Travolta',
            'Samuel L. Jackson',
            'Sandra Bullock',
            'George Clooney',
        ];

        foreach ($names as $name) {
            Actor::firstOrCreate(['actor_name' => $name]);
        }
    }
}
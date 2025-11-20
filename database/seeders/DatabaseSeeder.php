<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@letterboxd-uam.local'],
            [
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('Cr!quaL0v3r_1234'),
                'role' => 'A',
                'registration_date' => now(),
            ]
        );
        $this->call([
            DirectorSeeder::class,
            GenreSeeder::class,
            MovieSeeder::class,
            ActorSeeder::class,
            MovieCastSeeder::class,
        ]);
    }
}

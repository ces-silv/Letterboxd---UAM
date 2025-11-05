<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $primaryKey = 'movie_id';

    protected $fillable = [
        'title',
        'release_date',
        'director_id',
        'synopsis',
        'duration',
        'poster_path',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }

    public function cast()
    {
        return $this->hasMany(MovieCast::class, 'movie_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movie_cast', 'movie_id', 'actor_id')
                    ->withPivot('character_name')
                    ->withTimestamps();
    }
}
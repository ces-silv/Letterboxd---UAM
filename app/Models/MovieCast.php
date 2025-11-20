<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCast extends Model
{
    use HasFactory;

    protected $table = 'movie_cast';
    protected $primaryKey = 'cast_id';

    protected $fillable = [
        'movie_id',
        'actor_id',
        'character_name',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id');
    }
}
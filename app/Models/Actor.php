<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $primaryKey = 'actor_id';

    protected $fillable = [
        'actor_name',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_cast', 'actor_id', 'movie_id')
                    ->withPivot('character_name')
                    ->withTimestamps();
    }

    public function cast()
    {
        return $this->hasMany(MovieCast::class, 'actor_id');
    }
}
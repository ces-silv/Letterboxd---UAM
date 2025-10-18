<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $primaryKey = 'director_id';

    protected $fillable = [
        'director_name',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class, 'director_id');
    }
}
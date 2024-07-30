<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'species',
        'type',
        'gender',
        'origin',
        'location',
        'image',
    ];

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'episode_character', 'character_id', 'episode_id');
    }
}

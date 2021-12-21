<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'genre_id',
        'name',
        'type',
        'year',
        'image',
    ];

    public function Artist() {
        return $this->belongsTo(Artist::class);
    }

    public function Genre() {
        return $this->belongsTo(Genre::class);
    }

    public function Song() {
        return $this->hasMany(Song::class);
    }
}

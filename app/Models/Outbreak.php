<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'dated',
        'track_link',
        'location'
    ];

    public function Song() {
        return $this->belongsTo(Song::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'music_awards_releases_id',
        'award_name',
        'artist_id',
        'album_id',
        'song_id',
        'image',
        'is_featured'
    ];

    public function Release() {
        return $this->belongsTo(MusicAwardsReleases::class);
    }

    public function Artist() {
        return $this->belongsTo(Artist::class);
    }

    public function Album() {
        return $this->belongsTo(Album::class);
    }

    public function Song() {
        return $this->belongsTo(Song::class);
    }
}

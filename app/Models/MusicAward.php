<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMusicAward
 */
class MusicAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'release',
        'award_name',
        'artist_id',
        'album_id',
        'song_id',
        'image'
    ];

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

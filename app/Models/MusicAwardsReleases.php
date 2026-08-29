<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMusicAwardsReleases
 */
class MusicAwardsReleases extends Model
{
    use HasFactory;

    protected $fillable = [
        'release',
        'banner_image',
        'is_live',
    ];

    public function MusicAward()
    {
        return $this->hasMany(MusicAward::class, 'music_awards_releases_id');
    }
}

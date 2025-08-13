<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Song
 *
 * @property int $id
 * @property string $name
 * @property int $album_id
 * @property string|null $track_link
 * @property string|null $type
 * @property int $votes
 * @property int $is_charted
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Album $Album
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chart> $Chart
 * @property-read int|null $chart_count
 * @property-read \App\Models\User|null $User
 * @method static \Illuminate\Database\Eloquent\Builder|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereIsCharted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereTrackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperSong
 */
class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'name',
        'track_link',
        'type',
        'votes',
        'is_charted',
    ];

    public function Chart() {
        return $this->hasMany(Chart::class);
    }

    public function Album() {
        return $this->belongsTo(Album::class);
    }

    public function User() {
        return $this->belongsTo(User::class);
    }
}

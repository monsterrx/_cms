<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Outbreak
 *
 * @property int $id
 * @property int $song_id
 * @property string $dated
 * @property string $track_link
 * @property string|null $location
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Song $Song
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak query()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereTrackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperOutbreak
 */
class Outbreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'dated',
        'track_link',
        'location',
    ];

    public function Song()
    {
        return $this->belongsTo(Song::class);
    }
}

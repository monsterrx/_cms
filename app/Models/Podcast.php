<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Podcast
 *
 * @property int $id
 * @property int $show_id
 * @property string $episode
 * @property string $date
 * @property string $link
 * @property string|null $image
 * @property string|null $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Show $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast query()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereEpisode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperPodcast
 */
class Podcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'episode',
        'date',
        'link',
        'image',
        'location'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }
}

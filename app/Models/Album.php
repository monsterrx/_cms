<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Album
 *
 * @property int $id
 * @property string $name
 * @property string $year
 * @property string $type
 * @property int $artist_id
 * @property int $genre_id
 * @property string $image
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Artist $Artist
 * @property-read \App\Models\Genre $Genre
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $Song
 * @property-read int|null $song_count
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereYear($value)
 * @mixin \Eloquent
 * @mixin IdeHelperAlbum
 */
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

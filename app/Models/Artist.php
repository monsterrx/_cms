<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Artist
 *
 * @property int $id
 * @property string $name
 * @property string $country
 * @property string $type
 * @property string|null $image
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int, Album> $Album
 * @property-read int|null $album_count
 * @property-read Collection<int, Indie> $Indie
 * @property-read int|null $indie_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperArtist
 */
class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'type',
        'image',
    ];

    public function Album()
    {
        return $this->hasMany(Album::class);
    }

    public function Indie()
    {
        return $this->hasMany(Indie::class);
    }
}

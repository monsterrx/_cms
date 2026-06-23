<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Indie
 *
 * @property int $id
 * @property int $artist_id
 * @property string $introduction
 * @property string|null $image
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Artist $Artist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Feature> $Feature
 * @property-read int|null $feature_count
 * @method static \Illuminate\Database\Eloquent\Builder|Indie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperIndie
 */
class Indie extends Model
{
    use HasFactory;

    protected $table = 'indiegrounds';

    protected $fillable = [
        'artist_id',
        'introduction',
        'image',
        'location'
    ];

    public function Artist() {
        return $this->belongsTo(Artist::class);
    }

    public function Feature() {
        return $this->hasMany(Feature::class, 'independent_id');
    }
}

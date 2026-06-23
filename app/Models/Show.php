<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Show
 *
 * @property int $id
 * @property string $slug_string
 * @property string $title
 * @property string $front_description
 * @property string $description
 * @property string $icon
 * @property string $header_image
 * @property string|null $background_image
 * @property int $is_special
 * @property int $is_active
 * @property string|null $location
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Photo> $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jock> $Jock
 * @property-read int|null $jock_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Social> $Link
 * @property-read int|null $link_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Podcast> $Podcast
 * @property-read int|null $podcast_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timeslot> $Timeslot
 * @property-read int|null $timeslot_count
 * @method static \Illuminate\Database\Eloquent\Builder|Show newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show query()
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereFrontDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereHeaderImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereSlugString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperShow
 */
class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'front_description',
        'description',
        'slug_string',
        'icon',
        'header_image',
        'background_image',
        'is_special',
        'is_active',
        'location'
    ];

    public function Jock() {
        return $this->belongsToMany(Jock::class);
    }

    public function Timeslot() {
        return $this->hasMany(Timeslot::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }

    public function Link() {
        return $this->hasMany(Social::class);
    }

    public function Podcast() {
        return $this->hasMany(Podcast::class);
    }
}

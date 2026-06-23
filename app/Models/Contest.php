<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contest
 *
 * @property int $id
 * @property string $image
 * @property string $code
 * @property string $name
 * @property string $is_restricted
 * @property string $is_active
 * @property string $type
 * @property string $description
 * @property string|null $location
 * @property string|null $line1
 * @property string|null $line2
 * @property string|null $line3
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contestant> $Contestant
 * @property-read int|null $contestant_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereIsRestricted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperContest
 */
class Contest extends Model
{
    use HasFactory;

    protected $table = 'giveaways';

    protected $fillable = [
        'name',
        'type',
        'description',
        'line1',
        'line2',
        'line3',
        'is_restricted',
        'image',
        'code',
        'is_active',
        'location'
    ];

    public function Contestant() {
        return $this->belongsToMany(Contestant::class, 'contestant_giveaway', 'contestant_id', 'giveaway_id');
    }
}

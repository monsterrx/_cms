<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Award
 *
 * @property int $id
 * @property string $name
 * @property int|null $jock_id
 * @property int|null $show_id
 * @property string $title
 * @property string $location
 * @property string|null $description
 * @property string $year
 * @property int $is_special
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Award newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award query()
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereYear($value)
 * @mixin \Eloquent
 * @mixin IdeHelperAward
 */
class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'jock_id',
        'show_id',
        'name',
        'title',
        'description',
        'year',
        'special',
        'location'
    ];

    public function Jock() {
        return $this->belongsTo(Jock::class);
    }

    public function Show() {
        return $this->belongsTo(Show::class);
    }
}

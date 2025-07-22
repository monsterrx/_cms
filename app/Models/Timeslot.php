<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Timeslot
 *
 * @property int $id
 * @property int|null $show_id
 * @property string $day
 * @property string $start
 * @property string $end
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jock> $Jock
 * @property-read int|null $jock_count
 * @property-read \App\Models\Show|null $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTimeslot
 */
class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'day',
        'start',
        'end',
        'location'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }

    public function Jock() {
        return $this->belongsToMany(Jock::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Jock
 *
 * @property int $id
 * @property int $employee_id
 * @property string $slug_string
 * @property string $name
 * @property string|null $moniker
 * @property string|null $description
 * @property string $main_image
 * @property string|null $background_image
 * @property string|null $profile_image
 * @property string $is_active
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Award> $Award
 * @property-read int|null $award_count
 * @property-read \App\Models\Employee $Employee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fact> $Fact
 * @property-read int|null $fact_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Photo> $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Social> $Link
 * @property-read int|null $link_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Show> $Show
 * @property-read int|null $show_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timeslot> $Timeslot
 * @property-read int|null $timeslot_count
 * @method static \Illuminate\Database\Eloquent\Builder|Jock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereMoniker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereSlugString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperJock
 */
class Jock extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'slug_string',
        'name',
        'moniker',
        'description',
        'profile_image',
        'background_image',
        'main_image',
        'is_active'
    ];

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }

    public function Fact() {
        return $this->hasMany(Fact::class, 'jock_id');
    }

    public function Show() {
        return $this->belongsToMany(Show::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class, 'jock_id');
    }

    public function Link() {
        return $this->hasMany(Social::class, 'jock_id');
    }

    public function Award() {
        return $this->hasMany(Award::class, 'jock_id');
    }

    public function Timeslot() {
        return $this->belongsToMany(Timeslot::class);
    }

}

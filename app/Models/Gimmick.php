<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Gimmick
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string|null $description
 * @property string|null $location
 * @property string|null $sub_description
 * @property string|null $image
 * @property int $school_id
 * @property int|null $is_published
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereSubDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperGimmick
 */
class Gimmick extends Model
{
    use HasFactory;

    protected $table = 'gimikboards';

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'description',
        'sub_description',
        'school_id',
        'image',
        'location',
        'is_published'
    ];

    public function School() {
        return $this->belongsTo(School::class);
    }
}

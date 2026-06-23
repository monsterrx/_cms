<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Batch
 *
 * @property int $id
 * @property string $semester
 * @property int $number
 * @property string $start_year
 * @property string $end_year
 * @property string|null $description
 * @property string|null $image
 * @property string|null $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Photo> $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sponsor> $Sponsor
 * @property-read int|null $sponsor_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $Student
 * @property-read int|null $student_count
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperBatch
 */
class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester',
        'number',
        'start_year',
        'end_year',
        'description',
        'image',
        'location'
    ];

    public function Student() {
        return $this->belongsToMany(Student::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }

    public function Sponsor() {
        return $this->belongsToMany(Sponsor::class);
    }
}

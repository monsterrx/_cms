<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property int $school_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $course
 * @property int $year_level
 * @property string|null $location
 * @property string|null $data
 * @property string|null $image
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $Batch
 * @property-read int|null $batch_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Scholar> $Scholar
 * @property-read int|null $scholar_count
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereYearLevel($value)
 * @mixin \Eloquent
 * @mixin IdeHelperStudent
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'first_name',
        'last_name',
        'course',
        'year_level',
        'data',
        'scholar_type',
        'image'
    ];

    public function School() {
        return $this->belongsTo(School::class);
    }

    public function Batch() {
        return $this->belongsToMany(Batch::class);
    }

    public function Scholar() {
        return $this->hasMany(Scholar::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentJock
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 * @property string|null $description
 * @property string|null $image
 * @property int $school_id
 * @property int|null $position
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentJockBatch> $Batch
 * @property-read int|null $batch_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Photo> $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Social> $Link
 * @property-read int|null $link_count
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperStudentJock
 */
class StudentJock extends Model
{
    use HasFactory;

    protected $table = 'student_jocks';

    protected $fillable = [
        'school_id',
        'first_name',
        'last_name',
        'nickname',
        'image',
        'description',
        'position',
    ];

    public function Batch() {
        return $this->belongsToMany(StudentJockBatch::class);
    }

    public function School() {
        return $this->belongsTo(School::class);
    }

    public function Link() {
        return $this->hasMany(Social::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }
}

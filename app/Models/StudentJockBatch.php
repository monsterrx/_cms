<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentJockBatch
 *
 * @property int $id
 * @property string $batch_number
 * @property string $start_year
 * @property string $end_year
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentJock> $Student
 * @property-read int|null $student_count
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperStudentJockBatch
 */
class StudentJockBatch extends Model
{
    use HasFactory;

    protected $table = 'student_jocks_batches';

    protected $fillable = [
        'batch_number',
        'start_year',
        'end_year'
    ];

    public function Student() {
        return $this->belongsToMany(StudentJock::class);
    }
}

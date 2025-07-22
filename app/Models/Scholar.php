<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Scholar
 *
 * @property int $id
 * @property int $batch_id
 * @property int $student_id
 * @property int $scholar_type
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Batch|null $Batch
 * @property-read \App\Models\Student|null $Student
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereScholarType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperScholar
 */
class Scholar extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'scholar_type',
    ];

    public function Student() {
        return $this->belongsTo(Student::class);
    }

    public function Batch() {
        return $this->belongsTo(Batch::class);
    }
}

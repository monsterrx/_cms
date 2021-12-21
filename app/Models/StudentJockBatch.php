<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

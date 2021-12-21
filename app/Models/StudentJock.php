<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'position',
    ];

    public function Batch() {
        return $this->belongsToMany(StudentJockBatch::class);
    }

    public function School() {
        return $this->belongsTo(School::class);
    }
}

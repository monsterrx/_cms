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

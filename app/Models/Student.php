<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

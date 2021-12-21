<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'seal',
        'location'
    ];

    public function Gimikboard() {
        return $this->hasMany(Gimmick::class);
    }

    public function Student() {
        return $this->hasMany(Student::class);
    }
}

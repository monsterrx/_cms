<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'designation_id',
        'employee_number',
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'contact_number',
        'address',
        'location'
    ];

    public function User() {
        return $this->hasMany(User::class);
    }

    public function Designation() {
        return $this->belongsTo(Designation::class);
    }

    public function Jock() {
        return $this->hasMany(Jock::class);
    }

    public function Article() {
        return $this->hasMany(Article::class);
    }

    public function Votes() {
        return $this->hasMany(Vote::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jock extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'slug_string',
        'name',
        'moniker',
        'description',
        'profile_image',
        'background_image',
        'main_image',
        'is_active'
    ];

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }

    public function Fact() {
        return $this->hasMany(Fact::class, 'jock_id');
    }

    public function Show() {
        return $this->belongsToMany(Show::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class, 'jock_id');
    }

    public function Link() {
        return $this->hasMany(Social::class, 'jock_id');
    }

    public function Award() {
        return $this->hasMany(Award::class, 'jock_id');
    }

    public function Timeslot() {
        return $this->belongsToMany(Timeslot::class);
    }

}

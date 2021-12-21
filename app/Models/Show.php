<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'front_description',
        'description',
        'slug_string',
        'icon',
        'header_image',
        'background_image',
        'is_special',
        'is_active',
        'location'
    ];

    public function Jock() {
        return $this->belongsToMany(Jock::class);
    }

    public function Timeslot() {
        return $this->hasMany(Timeslot::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }

    public function Link() {
        return $this->hasMany(Social::class);
    }

    public function Podcast() {
        return $this->hasMany(Podcast::class);
    }
}

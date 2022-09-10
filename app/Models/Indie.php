<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indie extends Model
{
    use HasFactory;

    protected $table = 'indiegrounds';

    protected $fillable = [
        'artist_id',
        'introduction',
        'image',
        'location'
    ];

    public function Artist() {
        return $this->belongsTo(Artist::class);
    }

    public function Feature() {
        return $this->hasMany(Feature::class, 'independent_id');
    }
}

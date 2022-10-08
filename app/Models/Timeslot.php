<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'day',
        'start',
        'end',
        'location'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }

    public function Jock() {
        return $this->belongsToMany(Jock::class);
    }
}

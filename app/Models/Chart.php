<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'position',
        'last_position',
        're_entry',
        'dated',
        'is_dropped',
        'daily',
        'local',
        'votes',
        'last_results',
        'phone_votes',
        'social_votes',
        'online_votes',
        'voted_at',
        'is_posted',
        'location'
    ];

    public function Song() {
        return $this->belongsTo(Song::class);
    }

    public function Votes() {
        return $this->hasMany(Vote::class);
    }

    public function Tally() {
        return $this->hasMany(Tally::class);
    }
}

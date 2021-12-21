<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'episode',
        'date',
        'link',
        'image',
        'location'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }
}

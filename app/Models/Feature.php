<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'featured_indiegrounds';

    protected $fillable = [
        'independent_id',
        'content',
        'month',
        'year',
        'location'
    ];

    public function Indie() {
        return $this->belongsTo(Indie::class, 'independent_id');
    }
}

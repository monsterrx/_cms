<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    use HasFactory;

    protected $fillable = [
        'jock_id',
        'content'
    ];

    public function Jock() {
        return $this->belongsTo(Jock::class);
    }
}

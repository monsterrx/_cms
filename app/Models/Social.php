<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = [
        'jock_id',
        'show_id',
        'article_id',
        'student_jock_id',
        'website',
        'url'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }

    public function Jock() {
        return $this->belongsTo(Jock::class);
    }

    public function Article() {
        return $this->belongsTo(Article::class);
    }

    public function StudentJock() {
        return $this->belongsTo(StudentJock::class);
    }
}

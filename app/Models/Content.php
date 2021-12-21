<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'sub_contents';

    protected $fillable = [
        'article_id',
        'content',
        'image'
    ];

    public function Article() {
        return $this->belongsTo(Article::class, 'article_id');
    }
}

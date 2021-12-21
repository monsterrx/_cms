<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relevant extends Model
{
    use HasFactory;

    protected $table = 'relateds';

    protected $fillable = [
        'article_id',
        'related_article'
    ];

    public function Article() {
        return $this->belongsTo(Article::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'mobile_app_assets';

    protected $fillable = [
        'logo',
        'chart_icon',
        'article_icon',
        'podcast_icon',
        'article_page_icon',
        'youtube_page_icon',
        'location'
    ];

    public function Title() {
        return $this->hasOne(Title::class);
    }
}

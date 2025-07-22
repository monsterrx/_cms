<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Title
 *
 * @property int $id
 * @property int|null $asset_id
 * @property string $chart_title
 * @property string $chart_sub_title
 * @property string $article_title
 * @property string $article_sub_title
 * @property string $podcast_title
 * @property string $podcast_sub_title
 * @property string $articles_main_page_title
 * @property string|null $articles_main_page_subtitle
 * @property string $podcast_main_page_title
 * @property string $youtube_main_page_title
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $Asset
 * @method static \Illuminate\Database\Eloquent\Builder|Title newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Title newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Title query()
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereArticleSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereArticleTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereArticlesMainPageSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereArticlesMainPageTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereChartSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereChartTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title wherePodcastMainPageTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title wherePodcastSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title wherePodcastTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Title whereYoutubeMainPageTitle($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTitle
 */
class Title extends Model
{
    use HasFactory;

    protected $table = 'mobile_app_titles';

    protected $fillable = [
        'chart_title',
        'chart_sub_title',
        'article_title',
        'article_sub_title',
        'podcast_title',
        'podcast_sub_title',
        'articles_main_page_title',
        'podcast_main_page_title',
        'youtube_main_page_title',
        'location'
    ];

    public function Asset() {
        return $this->belongsTo(Asset::class);
    }
}

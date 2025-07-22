<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Asset
 *
 * @property int $id
 * @property string $logo
 * @property string $chart_icon
 * @property string $article_icon
 * @property string $podcast_icon
 * @property string $article_page_icon
 * @property string $youtube_page_icon
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Title|null $Title
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereArticleIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereArticlePageIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereChartIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePodcastIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereYoutubePageIcon($value)
 * @mixin \Eloquent
 * @mixin IdeHelperAsset
 */
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

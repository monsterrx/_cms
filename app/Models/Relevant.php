<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Relevant
 *
 * @property int $id
 * @property int $article_id
 * @property int $related_article_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article $Article
 * @property-read \App\Models\Article $RelatedArticle
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereRelatedArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRelevant
 */
class Relevant extends Model
{
    use HasFactory;

    protected $table = 'relateds';

    protected $fillable = [
        'article_id',
        'related_article_id'
    ];

    public function Article() {
        return $this->belongsTo(Article::class);
    }

    public function RelatedArticle() {
        return $this->belongsTo(Article::class, 'related_article_id');
    }
}

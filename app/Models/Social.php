<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Social
 *
 * @property int $id
 * @property int|null $jock_id
 * @property int|null $article_id
 * @property int|null $show_id
 * @property int|null $student_jock_id
 * @property string $website
 * @property string $url
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article|null $Article
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @property-read \App\Models\StudentJock|null $StudentJock
 * @method static \Illuminate\Database\Eloquent\Builder|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereStudentJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereWebsite($value)
 * @mixin \Eloquent
 * @mixin IdeHelperSocial
 */
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

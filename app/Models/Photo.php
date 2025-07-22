<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property int|null $jock_id
 * @property int|null $article_id
 * @property int|null $show_id
 * @property int|null $batch_id
 * @property int|null $student_jock_id
 * @property string $file
 * @property string|null $name
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article|null $Article
 * @property-read \App\Models\Batch|null $Batch
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @property-read \App\Models\StudentJock|null $StudentJock
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereStudentJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperPhoto
 */
class Photo extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'jock_id',
        'article_id',
        'show_id',
        'batch_id',
        'student_jock_id',
        'file',
        'name',
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

    public function Batch() {
        return $this->belongsTo(Batch::class);
    }

    public function StudentJock() {
        return $this->belongsTo(StudentJock::class);
    }
}

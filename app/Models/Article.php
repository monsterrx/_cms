<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int|null $employee_id
 * @property string $unique_id
 * @property string $title
 * @property string $heading
 * @property string|null $location
 * @property string|null $published_at
 * @property string $image
 * @property int $category_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Category $Category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Content> $Content
 * @property-read int|null $content_count
 * @property-read \App\Models\Employee|null $Employee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Photo> $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Relevant> $Relevant
 * @property-read int|null $relevant_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Social> $Social
 * @property-read int|null $social_count
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperArticle
 */
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'employee_id',
        'category_id',
        'title',
        'heading',
        'published_at',
        'image',
        'location'
    ];

    public function Category() {
        return $this->belongsTo(Category::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }

    public function Social() {
        return $this->hasMany(Social::class);
    }

    public function Relevant() {
        return $this->hasMany(Relevant::class);
    }

    public function Content() {
        return $this->hasMany(Content::class);
    }

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }
}

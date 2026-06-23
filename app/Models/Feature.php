<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feature
 *
 * @property int $id
 * @property int $indieground_id
 * @property string $content
 * @property string $location
 * @property string|null $month
 * @property string|null $year
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Indie $Indie
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereIndiegroundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereYear($value)
 * @mixin \Eloquent
 * @mixin IdeHelperFeature
 */
class Feature extends Model
{
    protected $table = 'featured_indiegrounds';

    protected $fillable = [
        'independent_id',
        'header',
        'content',
        'month',
        'year',
        'location'
    ];

    public function Indie() {
        return $this->belongsTo(Indie::class, 'indieground_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\StreamLink
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $location
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreamLink whereUrl($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperStreamLink
 */
class StreamLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'location',
    ];
}

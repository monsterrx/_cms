<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Header
 *
 * @property int $id
 * @property int $number
 * @property string $image
 * @property string $title
 * @property string|null $sub_title
 * @property string|null $location
 * @property string|null $link
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Header newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Header newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Header query()
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperHeader
 */
class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'image',
        'title',
        'sub_title',
        'link',
        'location'
    ];
}

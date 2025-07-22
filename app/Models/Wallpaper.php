<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wallpaper
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string|null $location
 * @property string $device
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperWallpaper
 */
class Wallpaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'location',
        'device',
    ];
}

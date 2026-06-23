<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Fact
 *
 * @property int $id
 * @property int $jock_id
 * @property string $content
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Jock $Jock
 * @method static \Illuminate\Database\Eloquent\Builder|Fact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperFact
 */
class Fact extends Model
{
    use HasFactory;

    protected $fillable = [
        'jock_id',
        'content'
    ];

    public function Jock() {
        return $this->belongsTo(Jock::class);
    }
}

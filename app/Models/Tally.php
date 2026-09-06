<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tally
 *
 * @property int $id
 * @property string $result
 * @property string $last_result
 * @property int|null $chart_id
 * @property string|null $dated
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property-read Chart|null $Chart
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Tally newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereLastResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperTally
 */
class Tally extends Model
{
    use HasFactory;

    protected $table = 'tallies';

    protected $fillable = [
        'chart_id',
        'result',
        'chart_type',
        'last_result',
        'dated',
    ];

    public function Chart()
    {
        return $this->belongsTo(Chart::class);
    }
}

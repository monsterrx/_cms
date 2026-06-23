<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vote
 *
 * @property int $id
 * @property string|null $action
 * @property int|null $chart_id
 * @property int|null $employee_id
 * @property string|null $dated
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Chart|null $Chart
 * @property-read \App\Models\Employee|null $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperVote
 */
class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'chart_id',
        'employee_id',
        'action',
        'dated'
    ];

    public function Chart() {
        return $this->belongsTo(Chart::class);
    }

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }
}

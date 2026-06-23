<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bugs
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $employee_id
 * @property string|null $location
 * @property int $is_resolved
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereIsResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperBugs
 */
class Bugs extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'title',
        'description',
        'image',
        'employee_id',
        'location',
        'is_resolved'
    ];

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }
}

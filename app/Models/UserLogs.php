<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserLogs
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $action
 * @property int $employee_id
 * @property string $location
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Employee $Employee
 * @property-read \App\Models\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogs whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperUserLogs
 */
class UserLogs extends Model {

    protected $dates = [
        'deleted_at'
    ];

	protected $fillable = [
	    'user_id',
        'action',
        'employee_id',
        'location'
    ];

	public function User(){
		return $this->belongsTo(User::class);
	}

	public function Employee(){
		return $this->belongsTo(Employee::class);
	}
}

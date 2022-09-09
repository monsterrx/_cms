<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

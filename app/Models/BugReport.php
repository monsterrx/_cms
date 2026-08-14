<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class BugReport extends Model
{
    use SoftDeletes;

    protected $table = 'reports';

    protected $guarded = [];

    protected $casts = [
        'is_resolved' => 'boolean',
        'notification_sent_at' => 'datetime',
        'notification_failed_at' => 'datetime',
    ];
}

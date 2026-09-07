<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignationGrant extends Model
{
    protected $fillable = ['designation_id', 'section_slug', 'item_slug', 'can_write'];

    protected $casts = ['can_write' => 'boolean'];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}

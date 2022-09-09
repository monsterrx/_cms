<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'employee_id',
        'category_id',
        'title',
        'heading',
        'published_at',
        'image',
        'location'
    ];

    public function Category() {
        return $this->belongsTo(Category::class);
    }

    public function Image() {
        return $this->hasMany(Photo::class);
    }

    public function Social() {
        return $this->hasMany(Social::class);
    }

    public function Relevant() {
        return $this->hasMany(Relevant::class);
    }

    public function Content() {
        return $this->hasMany(Content::class);
    }

    public function Employee() {
        return $this->belongsTo(Employee::class);
    }
}

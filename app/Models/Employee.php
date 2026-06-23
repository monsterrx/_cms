<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property int $designation_id
 * @property string $employee_number
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $gender
 * @property string|null $birthday
 * @property string|null $contact_number
 * @property string|null $address
 * @property string $location
 * @property int|null $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $Article
 * @property-read int|null $article_count
 * @property-read \App\Models\Designation $Designation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jock> $Jock
 * @property-read int|null $jock_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $User
 * @property-read int|null $user_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $Votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperEmployee
 */
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'designation_id',
        'employee_number',
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'contact_number',
        'address',
        'is_active',
        'location'
    ];

    public function User() {
        return $this->hasMany(User::class);
    }

    public function Designation() {
        return $this->belongsTo(Designation::class);
    }

    public function Jock() {
        return $this->hasMany(Jock::class);
    }

    public function Article() {
        return $this->hasMany(Article::class);
    }

    public function Votes() {
        return $this->hasMany(Vote::class);
    }
}

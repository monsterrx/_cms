<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contestant
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $birthday
 * @property string $email
 * @property string $phone_number
 * @property string $password
 * @property string $city
 * @property int|null $is_verified
 * @property string|null $image
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contest> $Contest
 * @property-read int|null $contest_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperContestant
 */
class Contestant extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use HasFactory, Authenticatable, CanResetPassword;

    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'birthday',
        'city',
        'email',
        'password'
    ];

    public function Contest() {
        return $this->belongsToMany(Contest::class, 'contestant_giveaway', 'giveaway_id', 'contestant_id');
    }
}

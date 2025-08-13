<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Chart
 *
 * @property int $id
 * @property int $song_id
 * @property int $position
 * @property string|null $last_position
 * @property string $re_entry
 * @property string $dated
 * @property string|null $location
 * @property int $daily
 * @property int $throwback
 * @property int $local
 * @property int|null $is_dropped
 * @property string|null $votes
 * @property string|null $last_results
 * @property string $phone_votes
 * @property string $social_votes
 * @property string $online_votes
 * @property string|null $voted_at
 * @property int $is_posted
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Song $Song
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tally> $Tally
 * @property-read int|null $tally_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $Votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Chart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereDaily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereIsDropped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereIsPosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereLastPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereLastResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereOnlineVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart wherePhoneVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereReEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereSocialVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereVotedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereVotes($value)
 * @mixin \Eloquent
 * @mixin IdeHelperChart
 */
class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'position',
        'last_position',
        're_entry',
        'dated',
        'is_dropped',
        'daily',
        'throwback',
        'local',
        'votes',
        'last_results',
        'phone_votes',
        'social_votes',
        'online_votes',
        'voted_at',
        'is_posted',
        'location'
    ];

    public function Song() {
        return $this->belongsTo(Song::class);
    }

    public function Votes() {
        return $this->hasMany(Vote::class);
    }

    public function Tally() {
        return $this->hasMany(Tally::class);
    }

    public function LatestChartTally() {
        return $this->hasOne(Tally::class)
            ->where('chart_type', '=', 'charts')
            ->latest('dated');
    }

    public function LatestDailyTally() {
        return $this->hasOne(Tally::class)
            ->where('chart_type', '=', 'daily')
            ->latest('dated');
    }
}

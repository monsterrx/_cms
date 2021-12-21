<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Album
 *
 * @property int $id
 * @property string $name
 * @property string|null $year
 * @property string|null $type
 * @property int $artist_id
 * @property int $genre_id
 * @property string|null $image
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Artist $Artist
 * @property-read \App\Models\Genre $Genre
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Song[] $Song
 * @property-read int|null $song_count
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereYear($value)
 */
	class Album extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Article
 *
 * @property int $id
 * @property int|null $employee_id
 * @property string $unique_id
 * @property string $title
 * @property string $heading
 * @property string $location
 * @property string|null $published_at
 * @property string $image
 * @property int $categories_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Category $Category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $Content
 * @property-read int|null $content_count
 * @property-read \App\Models\Employee|null $Employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Relevant[] $Relevant
 * @property-read int|null $relevant_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $Social
 * @property-read int|null $social_count
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 */
	class Article extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Artist
 *
 * @property int $id
 * @property string $name
 * @property string|null $country
 * @property string|null $type
 * @property string|null $image
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $Album
 * @property-read int|null $album_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Indie[] $Indie
 * @property-read int|null $indie_count
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereUpdatedAt($value)
 */
	class Artist extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Award
 *
 * @property int $id
 * @property string $name
 * @property int|null $jock_id
 * @property int|null $show_id
 * @property string $title
 * @property string $location
 * @property string|null $description
 * @property string $year
 * @property int $is_special
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Award newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award query()
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereYear($value)
 */
	class Award extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Batch
 *
 * @property int $id
 * @property string $semester
 * @property int $number
 * @property string $start_year
 * @property string $end_year
 * @property string|null $description
 * @property string|null $image
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $Scholar
 * @property-read int|null $scholar_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sponsor[] $Sponsor
 * @property-read int|null $sponsor_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $Student
 * @property-read int|null $student_count
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereUpdatedAt($value)
 */
	class Batch extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Bugs
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $employee_id
 * @property string $location
 * @property int $is_resolved
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereIsResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bugs whereUpdatedAt($value)
 */
	class Bugs extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $Article
 * @property-read int|null $article_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Chart
 *
 * @property int $id
 * @property int $song_id
 * @property int $position
 * @property int|null $last_position
 * @property int|null $re_entry
 * @property string $dated
 * @property string $location
 * @property int $daily
 * @property int $local
 * @property int|null $is_dropped
 * @property string|null $votes
 * @property string|null $last_results
 * @property string|null $phone_votes
 * @property string|null $social_votes
 * @property string|null $online_votes
 * @property string|null $voted_at
 * @property int $is_posted
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Song $Song
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tally[] $Tally
 * @property-read int|null $tally_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote[] $Votes
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
 */
	class Chart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Content
 *
 * @property int $id
 * @property int $article_id
 * @property string|null $content
 * @property string|null $image
 * @property string|null $link
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article $Article
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 */
	class Content extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contest
 *
 * @property int $id
 * @property string $image
 * @property string $code
 * @property string $name
 * @property string $is_restricted
 * @property string $is_active
 * @property string $type
 * @property string $description
 * @property string $location
 * @property string|null $line1
 * @property string|null $line2
 * @property string|null $line3
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Contestant[] $Contestant
 * @property-read int|null $contestant_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereIsRestricted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLine3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contest whereUpdatedAt($value)
 */
	class Contest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contestant
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $birthday
 * @property string $email
 * @property string $phone_number
 * @property string $password
 * @property string $city
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Contest[] $Contest
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
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereUpdatedAt($value)
 */
	class Contestant extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\CanResetPassword {}
}

namespace App\Models{
/**
 * App\Models\Designation
 *
 * @property int $id
 * @property string|null $name
 * @property string $level
 * @property string|null $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Employee[] $Employee
 * @property-read int|null $employee_count
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereUpdatedAt($value)
 */
	class Designation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Employee
 *
 * @property int $id
 * @property string $employee_number
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string|null $birthday
 * @property string|null $contact_number
 * @property string|null $address
 * @property string $location
 * @property int $designation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $Article
 * @property-read int|null $article_count
 * @property-read \App\Models\Designation $Designation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Jock[] $Jock
 * @property-read int|null $jock_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $User
 * @property-read int|null $user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote[] $Votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Query\Builder|Employee onlyTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Employee withoutTrashed()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Fact
 *
 * @property int $id
 * @property int $jock_id
 * @property string $content
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Jock $Jock
 * @method static \Illuminate\Database\Eloquent\Builder|Fact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fact whereUpdatedAt($value)
 */
	class Fact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Feature
 *
 * @property int $id
 * @property int $independent_id
 * @property string $content
 * @property string $location
 * @property string|null $month
 * @property string|null $year
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Indie $Indie
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereIndependentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereYear($value)
 */
	class Feature extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $Album
 * @property-read int|null $album_count
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereUpdatedAt($value)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Gimmick
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $description
 * @property string $location
 * @property string|null $sub_description
 * @property string|null $image
 * @property int $school_id
 * @property int|null $is_published
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereSubDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gimmick whereUpdatedAt($value)
 */
	class Gimmick extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Header
 *
 * @property int $id
 * @property int $number
 * @property string $image
 * @property string $title
 * @property string|null $sub_title
 * @property string $location
 * @property string|null $link
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Header newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Header newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Header query()
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Header whereUpdatedAt($value)
 */
	class Header extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Indie
 *
 * @property int $id
 * @property int $artist_id
 * @property string $introduction
 * @property string|null $image
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Artist $Artist
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feature[] $Feature
 * @property-read int|null $feature_count
 * @method static \Illuminate\Database\Eloquent\Builder|Indie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indie whereUpdatedAt($value)
 */
	class Indie extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Jock
 *
 * @property int $id
 * @property int $employee_id
 * @property string $slug_string
 * @property string $name
 * @property string|null $moniker
 * @property string|null $description
 * @property string|null $profile_image
 * @property string|null $background_image
 * @property string|null $main_image
 * @property string $is_active
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Award[] $Award
 * @property-read int|null $award_count
 * @property-read \App\Models\Employee $Employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Fact[] $Fact
 * @property-read int|null $fact_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $Link
 * @property-read int|null $link_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Show[] $Show
 * @property-read int|null $show_count
 * @method static \Illuminate\Database\Eloquent\Builder|Jock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereMoniker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereSlugString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jock whereUpdatedAt($value)
 */
	class Jock extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $contact_number
 * @property string $topic
 * @property string $content
 * @property int $is_seen
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIsSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Outbreak
 *
 * @property int $id
 * @property int $song_id
 * @property string $dated
 * @property string|null $track_link
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Song $Song
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak query()
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereTrackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Outbreak whereUpdatedAt($value)
 */
	class Outbreak extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Photo
 *
 * @property int $id
 * @property int|null $jock_id
 * @property int|null $article_id
 * @property int|null $show_id
 * @property string|null $batch_id
 * @property string $file
 * @property string|null $name
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article|null $Article
 * @property-read \App\Models\Batch|null $Batch
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereUpdatedAt($value)
 */
	class Photo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Podcast
 *
 * @property int $id
 * @property int $show_id
 * @property string $episode
 * @property string $date
 * @property string $link
 * @property string|null $image
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Show $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast query()
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereEpisode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Podcast whereUpdatedAt($value)
 */
	class Podcast extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Relevant
 *
 * @property int $id
 * @property int $article_id
 * @property int $related_article
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article $Article
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereRelatedArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relevant whereUpdatedAt($value)
 */
	class Relevant extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Scholar
 *
 * @property int $id
 * @property int $batch_id
 * @property int $student_id
 * @property int $scholar_type
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Batch $Batch
 * @property-read \App\Models\Student $Student
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereScholarType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholar whereUpdatedAt($value)
 */
	class Scholar extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\School
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $seal
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gimmick[] $Gimikboard
 * @property-read int|null $gimikboard_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $Student
 * @property-read int|null $student_count
 * @method static \Illuminate\Database\Eloquent\Builder|School newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School query()
 * @method static \Illuminate\Database\Eloquent\Builder|School whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereSeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereUpdatedAt($value)
 */
	class School extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Show
 *
 * @property int $id
 * @property string $slug_string
 * @property string $title
 * @property string $front_description
 * @property string $description
 * @property string $icon
 * @property string|null $header_image
 * @property string|null $background_image
 * @property int $is_special
 * @property int $is_active
 * @property string $location
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $Image
 * @property-read int|null $image_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Jock[] $Jock
 * @property-read int|null $jock_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $Link
 * @property-read int|null $link_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Podcast[] $Podcast
 * @property-read int|null $podcast_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Timeslot[] $Timeslot
 * @property-read int|null $timeslot_count
 * @method static \Illuminate\Database\Eloquent\Builder|Show newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show query()
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereFrontDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereHeaderImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereSlugString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereUpdatedAt($value)
 */
	class Show extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Social
 *
 * @property int $id
 * @property int|null $jock_id
 * @property int|null $article_id
 * @property int|null $show_id
 * @property string $website
 * @property string|null $url
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Article|null $Article
 * @property-read \App\Models\Jock|null $Jock
 * @property-read \App\Models\Show|null $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereJockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereWebsite($value)
 */
	class Social extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Song
 *
 * @property int $id
 * @property string $name
 * @property int $album_id
 * @property string|null $track_link
 * @property string|null $type
 * @property int $is_charted
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Album $Album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chart[] $Chart
 * @property-read int|null $chart_count
 * @property-read \App\Models\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereIsCharted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereTrackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereUpdatedAt($value)
 */
	class Song extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Sponsor
 *
 * @property int $id
 * @property string $name
 * @property string|null $remarks
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Batch[] $Batch
 * @property-read int|null $batch_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sponsor whereUpdatedAt($value)
 */
	class Sponsor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Student
 *
 * @property int $id
 * @property int $school_id
 * @property string $first_name
 * @property string $last_name
 * @property string $course
 * @property int $year_level
 * @property string $location
 * @property string|null $data
 * @property string|null $image
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Batch[] $Batch
 * @property-read int|null $batch_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Scholar[] $Scholar
 * @property-read int|null $scholar_count
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereYearLevel($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StudentJock
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 * @property string|null $image
 * @property int $school_id
 * @property int|null $position
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentJockBatch[] $Batch
 * @property-read int|null $batch_count
 * @property-read \App\Models\School $School
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJock whereUpdatedAt($value)
 */
	class StudentJock extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StudentJockBatch
 *
 * @property int $id
 * @property int $batch_number
 * @property string $start_year
 * @property string $end_year
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentJock[] $Student
 * @property-read int|null $student_count
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereEndYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentJockBatch whereUpdatedAt($value)
 */
	class StudentJockBatch extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tally
 *
 * @property int $id
 * @property string|null $results
 * @property string|null $last_results
 * @property int|null $chart_id
 * @property string|null $dated
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Chart|null $Chart
 * @method static \Illuminate\Database\Eloquent\Builder|Tally newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereLastResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tally whereUpdatedAt($value)
 */
	class Tally extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Timeslot
 *
 * @property int $id
 * @property int $show_id
 * @property string $day
 * @property string $start
 * @property string $end
 * @property string $location
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Show $Show
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereUpdatedAt($value)
 */
	class Timeslot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $employee_number
 * @property int $employee_id
 * @property string $email
 * @property string $password
 * @property string|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Employee $Employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
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
 */
	class UserLogs extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Vote
 *
 * @property int $id
 * @property string|null $action
 * @property int|null $chart_id
 * @property int|null $employee_id
 * @property string|null $dated
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Chart|null $Chart
 * @property-read \App\Models\Employee|null $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUpdatedAt($value)
 */
	class Vote extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wallpaper
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $location
 * @property string $device
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallpaper whereUpdatedAt($value)
 */
	class Wallpaper extends \Eloquent {}
}


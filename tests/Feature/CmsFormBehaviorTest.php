<?php

namespace Tests\Feature;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use App\Support\ResourceDefinitionRegistry;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use ReflectionProperty;
use Tests\TestCase;

class CmsFormBehaviorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['sanctum.stateful' => ['localhost:9001']]);
        (new ReflectionProperty(ResourceDefinitionRegistry::class, 'columnCache'))->setValue(null, []);
        foreach (['designations', 'employees', 'schools', 'gimikboards', 'artists', 'albums', 'songs', 'headers', 'articles'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name): void {
                $table->id();
                $table->string('name')->nullable();
                $table->string('location')->default('mnl');
                if ($name === 'designations') {
                    $table->integer('level');
                }
                if ($name === 'employees') {
                    $table->string('employee_number')->unique();
                    $table->string('first_name');
                    $table->string('last_name');
                }
                if ($name === 'albums') {
                    $table->unsignedBigInteger('artist_id');
                }
                if ($name === 'songs') {
                    $table->unsignedBigInteger('album_id');
                    $table->string('type');
                    $table->string('track_link')->nullable();
                    $table->boolean('is_charted')->default(false);
                }
                if ($name === 'headers') {
                    $table->integer('number');
                }
                if ($name === 'gimikboards') {
                    $table->unsignedBigInteger('school_id');
                    $table->date('start_date');
                    $table->date('end_date');
                    $table->string('event_duration')->nullable();
                    $table->dateTime('published_at')->nullable();
                    $table->boolean('is_published')->default(false);
                }
                $table->timestamps();
                $table->softDeletes();
            });
        }
        $user = (new User)->forceFill(['id' => 1, 'employee_id' => 1]);
        $employee = (new Employee)->forceFill(['id' => 1, 'location' => 'mnl']);
        $employee->setRelation('Designation', (new Designation)->forceFill(['id' => 1, 'level' => 1]));
        $user->setRelation('Employee', $employee);
        Sanctum::actingAs($user);
    }

    protected function tearDown(): void
    {
        (new ReflectionProperty(ResourceDefinitionRegistry::class, 'columnCache'))->setValue(null, []);
        parent::tearDown();
    }

    public function test_selected_station_overrides_submitted_location_and_generates_legacy_staff_number(): void
    {
        foreach (['mnl', 'cbu', 'dav'] as $station) {
            $response = $this->withHeader('Referer', 'http://localhost:9001/')->withSession(['workspace.station_code' => $station])->postJson('/api/resources/staff/staffs', [
                'first_name' => 'Test', 'last_name' => 'Employee', 'location' => 'wrong', 'employee_number' => 'injected',
            ])->assertCreated()->assertJsonPath('data.record.location', $station);
            $this->assertMatchesRegularExpression('/^RX[A-Z0-9]{8}931$/', $response->json('data.record.employee_number'));
        }
    }

    public function test_designation_level_is_validated_as_one_through_nine(): void
    {
        $this->postJson('/api/resources/staff/designations', ['name' => 'Producer', 'level' => 10])->assertUnprocessable()->assertJsonValidationErrors('level');
        $this->postJson('/api/resources/staff/designations', ['name' => 'Producer', 'level' => '9'])->assertCreated();
    }

    public function test_song_album_must_belong_to_selected_artist_and_sample_requires_audio(): void
    {
        DB::table('artists')->insert([['id' => 1, 'name' => 'One'], ['id' => 2, 'name' => 'Two']]);
        DB::table('albums')->insert(['id' => 1, 'name' => 'Album', 'artist_id' => 1]);
        $this->postJson('/api/resources/music/songs', ['name' => 'Song', 'artist_id' => 2, 'album_id' => 1, 'type' => 'spotify', 'track_link' => 'https://open.spotify.com/track/example'])
            ->assertUnprocessable()->assertJsonValidationErrors('album_id');
        $this->postJson('/api/resources/music/songs', ['name' => 'Song', 'artist_id' => 1, 'album_id' => 1, 'type' => 'sample'])
            ->assertUnprocessable()->assertJsonValidationErrors('sample');
        $this->postJson('/api/resources/music/songs', ['name' => 'Song', 'artist_id' => 1, 'album_id' => 1, 'type' => 'spotify', 'track_link' => 'https://open.spotify.com/track/example', 'is_charted' => 1])
            ->assertCreated()->assertJsonPath('data.record.is_charted', 0);
    }

    public function test_inline_image_upload_returns_a_persistent_public_url(): void
    {
        $directory = storage_path('framework/testing/editor-image-'.Str::random(12));
        $this->app->usePublicPath($directory);
        config(['media.server_root' => null]);
        try {
            $response = $this->post('/api/editor-images', ['image' => UploadedFile::fake()->image('inline.jpg', 640, 480)], ['Accept' => 'application/json'])
                ->assertCreated();
            $name = basename($response->json('data.url'));
            $this->assertFileExists($directory.'/images/articles/'.$name);
            $this->assertStringContainsString('/images/articles/', $response->json('data.url'));
        } finally {
            File::deleteDirectory($directory);
        }
    }

    public function test_sample_song_upload_persists_audio_and_ignores_hidden_spotify_url(): void
    {
        $directory = storage_path('framework/testing/song-audio-'.Str::random(12));
        $this->app->usePublicPath($directory);
        DB::table('artists')->insert(['id' => 1, 'name' => 'Artist']);
        DB::table('albums')->insert(['id' => 1, 'name' => 'Album', 'artist_id' => 1]);
        try {
            $response = $this->post('/api/resources/music/songs', [
                'name' => 'Sample', 'artist_id' => 1, 'album_id' => 1, 'type' => 'sample',
                'sample' => UploadedFile::fake()->create('sample.mp3', 10, 'audio/mpeg'),
                'track_link' => 'ignored',
            ], ['Accept' => 'application/json'])->assertCreated();
            $name = $response->json('data.record.track_link');
            $this->assertStringEndsWith('.mp3', $name);
            $this->assertFileExists($directory.'/audios/'.$name);
        } finally {
            File::deleteDirectory($directory);
        }
    }

    public function test_graphic_order_is_automatically_assigned(): void
    {
        DB::table('headers')->insert(['name' => 'Existing', 'number' => 3]);
        $this->postJson('/api/resources/digital-content-programs/graphics-artist', ['name' => 'New', 'number' => 3])
            ->assertCreated()->assertJsonPath('data.record.number', 4);
    }

    public function test_event_duration_and_scheduled_publication(): void
    {
        $this->travelTo(now()->setDate(2026, 9, 1)->startOfDay());
        DB::table('schools')->insert(['id' => 1, 'name' => 'School']);
        $id = $this->postJson('/api/resources/events-scholarship/gimik-board', [
            'name' => 'Event', 'school_id' => 1, 'start_date' => '2026-09-01', 'end_date' => '2026-10-16',
            'published_at' => '2026-09-02 10:00:00', 'is_published' => 1,
        ])->assertCreated()->assertJsonPath('data.record.event_duration', '1 month and 15 days')
            ->assertJsonPath('data.record.is_published', 0)->json('data.record.id');
        $this->artisan('gimikboards:publish-scheduled')->assertSuccessful();
        $this->assertSame(0, DB::table('gimikboards')->where('id', $id)->value('is_published'));
        $this->travelTo(now()->setDate(2026, 9, 2)->setTime(10, 0));
        $this->artisan('gimikboards:publish-scheduled')->assertSuccessful();
        $this->assertSame(1, DB::table('gimikboards')->where('id', $id)->value('is_published'));
        $this->putJson('/api/resources/events-scholarship/gimik-board/'.$id, ['end_date' => '2026-08-01'])->assertUnprocessable()->assertJsonValidationErrors('end_date');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChartSchedulingWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        Sanctum::actingAs($this->administrator());

        DB::table('songs')->insert([
            ['id' => 1, 'name' => 'First Song', 'is_charted' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Second Song', 'is_charted' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_chart_entries_can_be_created_reordered_and_deleted(): void
    {
        $firstId = $this->postJson('/api/charts/workspace/entries', $this->entryPayload(1))
            ->assertCreated()
            ->assertJsonPath('message', 'Song added to the chart draft.')
            ->json('data.id');
        $secondId = $this->postJson('/api/charts/workspace/entries', $this->entryPayload(2))
            ->assertCreated()
            ->json('data.id');

        $this->putJson('/api/charts/workspace/order', [
            ...$this->chartSelection(),
            'ids' => [$secondId, $firstId],
        ])->assertOk();

        $this->assertDatabaseHas('charts', ['id' => $secondId, 'position' => 1]);
        $this->assertDatabaseHas('charts', ['id' => $firstId, 'position' => 2]);

        $this->deleteJson("/api/charts/workspace/entries/{$firstId}")
            ->assertOk()
            ->assertJsonPath('message', 'Chart entry removed successfully.');
        $this->assertNotNull(DB::table('charts')->where('id', $firstId)->value('deleted_at'));
    }

    public function test_chart_can_be_scheduled_and_published_when_due(): void
    {
        $this->travelTo(now()->startOfMinute());
        $this->postJson('/api/charts/workspace/entries', $this->entryPayload(1))->assertCreated();

        $scheduleId = $this->postJson('/api/charts/workspace/schedules', [
            ...$this->chartSelection(),
            'publish_at' => now()->addMinutes(5)->toDateTimeString(),
        ])->assertCreated()
            ->assertJsonPath('message', 'Chart publication scheduled successfully.')
            ->json('data.id');

        $this->artisan('charts:publish-scheduled')
            ->expectsOutput('Processed 0 scheduled chart publication(s).')
            ->assertSuccessful();

        $this->travel(6)->minutes();
        $this->artisan('charts:publish-scheduled')
            ->expectsOutput('Processed 1 scheduled chart publication(s).')
            ->assertSuccessful();

        $this->assertDatabaseHas('chart_publication_schedules', [
            'id' => $scheduleId,
            'status' => 'published',
        ]);
        $this->assertDatabaseHas('charts', ['song_id' => 1, 'is_posted' => 1]);
    }

    public function test_duplicate_and_past_schedules_are_rejected(): void
    {
        $this->postJson('/api/charts/workspace/entries', $this->entryPayload(1))->assertCreated();
        $payload = [
            ...$this->chartSelection(),
            'publish_at' => now()->addHour()->toDateTimeString(),
        ];

        $this->postJson('/api/charts/workspace/schedules', $payload)->assertCreated();
        $this->postJson('/api/charts/workspace/schedules', $payload)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'This chart already has a pending publication schedule.');
        $this->postJson('/api/charts/workspace/schedules', [
            ...$this->chartSelection(),
            'publish_at' => now()->subMinute()->toDateTimeString(),
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('publish_at');
    }

    public function test_chart_can_be_published_immediately(): void
    {
        $this->postJson('/api/charts/workspace/entries', $this->entryPayload(1))->assertCreated();

        $this->postJson('/api/charts/workspace/publish', $this->chartSelection())
            ->assertOk()
            ->assertJsonPath('data.published_entries', 1);

        $this->assertDatabaseHas('charts', ['song_id' => 1, 'is_posted' => 1]);
    }

    /** @return array{module: string, type: string, date: string} */
    private function chartSelection(): array
    {
        return ['module' => 'station', 'type' => 'official', 'date' => '2026-09-07'];
    }

    /** @return array{module: string, type: string, date: string, song_id: int} */
    private function entryPayload(int $songId): array
    {
        return [...$this->chartSelection(), 'song_id' => $songId];
    }

    private function createSchema(): void
    {
        Schema::create('songs', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->boolean('is_charted')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('charts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('song_id');
            $table->integer('position');
            $table->integer('last_position')->default(0);
            $table->date('dated');
            $table->boolean('re_entry')->default(false);
            $table->boolean('daily')->default(false);
            $table->boolean('playlist')->default(false);
            $table->boolean('throwback')->default(false);
            $table->boolean('local')->default(false);
            $table->boolean('is_dropped')->default(false);
            $table->string('location', 3)->default('mnl');
            $table->integer('votes')->default(0);
            $table->integer('last_results')->default(0);
            $table->integer('phone_votes')->default(0);
            $table->integer('social_votes')->default(0);
            $table->integer('online_votes')->default(0);
            $table->date('voted_at')->nullable();
            $table->boolean('is_posted')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('chart_publication_schedules', function (Blueprint $table): void {
            $table->id();
            $table->string('location', 3);
            $table->date('chart_date');
            $table->string('chart_type', 32);
            $table->dateTime('publish_at');
            $table->string('status', 20)->default('pending');
            $table->dateTime('published_at')->nullable();
            $table->text('failure_message')->nullable();
            $table->timestamps();
        });
    }

    private function administrator(): User
    {
        $user = (new User)->forceFill(['id' => 1, 'employee_id' => 1, 'email' => 'admin@example.com']);
        $employee = (new Employee)->forceFill(['id' => 1]);
        $designation = (new Designation)->forceFill(['id' => 1, 'level' => 1]);

        $employee->setRelation('Designation', $designation);
        $user->setRelation('Employee', $employee);

        return $user;
    }
}

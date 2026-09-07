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

class ResourceCrudWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('designations', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('level');
            $table->timestamps();
            $table->softDeletes();
        });

        Sanctum::actingAs($this->administrator());
    }

    public function test_shared_resource_endpoint_completes_a_crud_lifecycle(): void
    {
        $endpoint = '/api/resources/staff/designations';

        $createdId = $this->postJson($endpoint, ['name' => 'Test Producer', 'level' => 6])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.record.name', 'Test Producer')
            ->json('data.record.id');

        $this->getJson($endpoint)
            ->assertOk()
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonPath('data.records.0.id', $createdId);

        $this->getJson("{$endpoint}/{$createdId}")
            ->assertOk()
            ->assertJsonPath('data.record.name', 'Test Producer');

        $this->putJson("{$endpoint}/{$createdId}", ['name' => 'Senior Producer', 'level' => 5])
            ->assertOk()
            ->assertJsonPath('data.record.name', 'Senior Producer');

        $this->deleteJson("{$endpoint}/{$createdId}")
            ->assertOk()
            ->assertJsonPath('message', 'Record deleted successfully.');

        $this->assertNotNull(DB::table('designations')->where('id', $createdId)->value('deleted_at'));
        $this->getJson("{$endpoint}/{$createdId}")->assertNotFound();
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

<?php

namespace Tests\Feature;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_browser_not_found_response_uses_status_page(): void
    {
        $this->get('/missing-page')
            ->assertNotFound()
            ->assertSee('Page not found');
    }

    public function test_api_not_found_response_uses_safe_json_contract(): void
    {
        $this->getJson('/api/missing-page')->assertNotFound()->assertExactJson([
            'success' => false,
            'message' => 'The requested resource was not found.',
            'errors' => [],
            'code' => 'NOT_FOUND',
        ]);
    }

    public function test_resource_api_requires_authentication(): void
    {
        $this->getJson('/api/resources/staff/staffs')
            ->assertUnauthorized()
            ->assertJsonPath('code', 'UNAUTHENTICATED');
    }

    public function test_sanctum_authenticates_api_users(): void
    {
        Sanctum::actingAs($this->authenticatedUser());

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'cms@example.com');
    }

    public function test_administrator_can_switch_the_active_station(): void
    {
        $this->actingAs($this->authenticatedUserAtLevel(2));

        $this->withHeaders([
            'Origin' => 'http://localhost:9001',
            'Referer' => 'http://localhost:9001/dashboard',
        ])->putJson('/api/station', ['station' => 'cbu'])
            ->assertOk()
            ->assertJsonPath('data.station', 'cbu');

        $this->assertSame('cbu', session('workspace.station_code'));
    }

    public function test_non_administrator_cannot_switch_the_active_station(): void
    {
        $this->actingAs($this->authenticatedUserAtLevel(9));

        $this->withHeaders([
            'Origin' => 'http://localhost:9001',
            'Referer' => 'http://localhost:9001/dashboard',
        ])->putJson('/api/station', ['station' => 'dav'])
            ->assertForbidden()
            ->assertJsonPath('code', 'FORBIDDEN');
    }

    public function test_administrator_cannot_select_an_unknown_station(): void
    {
        $this->actingAs($this->authenticatedUserAtLevel(2));

        $this->putJson('/api/station', ['station' => 'invalid'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('station');
    }

    public function test_unknown_resource_returns_safe_not_found_response(): void
    {
        $this->actingAs($this->authenticatedUser());

        $this->getJson('/api/resources/not-real/not-real')
            ->assertNotFound()
            ->assertJsonPath('code', 'NOT_FOUND');
    }

    public function test_system_health_api_returns_database_status(): void
    {
        DB::shouldReceive('select')
            ->once()
            ->with('SELECT 1')
            ->andReturn([['1' => 1]]);

        $this->getJson('/api/system/health')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.application.status', 'operational')
            ->assertJsonPath('data.database.status', 'connected');
    }

    public function test_under_construction_page_is_available(): void
    {
        $this->actingAs($this->authenticatedUser());

        $this->get('/under-construction/articles')
            ->assertOk()
            ->assertSee('Articles');
    }

    public function test_workspace_route_is_available(): void
    {
        $this->actingAs($this->authenticatedUser());

        $this->get('/workspace/music/station-chart')
            ->assertOk()
            ->assertSee('station-chart');
    }

    public function test_unknown_workspace_item_returns_not_found(): void
    {
        $this->actingAs($this->authenticatedUser());

        $this->get('/workspace/music/not-a-real-tool')->assertNotFound();
    }

    private function authenticatedUser(): User
    {
        return $this->authenticatedUserAtLevel(9);
    }

    private function authenticatedUserAtLevel(int $level): User
    {
        $user = (new User())->forceFill([
            'id' => 1,
            'employee_id' => 1,
            'employee_number' => 'TEST0001',
            'email' => 'cms@example.com',
        ]);
        $employee = (new Employee())->forceFill(['id' => 1]);
        $designation = (new Designation())->forceFill(['id' => $level, 'level' => $level]);

        $employee->setRelation('Designation', $designation);
        $user->setRelation('Employee', $employee);

        return $user;
    }
}

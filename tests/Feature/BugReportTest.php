<?php

namespace Tests\Feature;

use App\Mail\BugReportSubmitted;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class BugReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_a_bug_report_with_a_pasted_screenshot(): void
    {
        Mail::fake();
        Storage::fake('local');
        $user = User::factory()->create(['email' => 'reporter@example.test']);

        $response = $this->actingAs($user)->postJson('/api/bug-reports', [
            'title' => 'Related article selector overlaps',
            'description' => '<p>The selector leaves its parent.</p><script>alert(1)</script>',
            'page_url' => 'http://localhost:9001/workspace/digital-content-programs/articles',
            'screenshots' => [UploadedFile::fake()->createWithContent(
                'overlap.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
            )],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.notification_sent', true);
        $reportId = $response->json('data.id');

        $this->assertDatabaseHas('reports', [
            'id' => $reportId,
            'reporter_email' => 'reporter@example.test',
            'title' => 'Related article selector overlaps',
        ]);
        $this->assertDatabaseCount('report_attachments', 1);
        $this->assertStringNotContainsString('script', (string) $this->app['db']->table('reports')->where('id', $reportId)->value('description'));
        Mail::assertSent(BugReportSubmitted::class, function (BugReportSubmitted $mail): bool {
            return $mail->hasTo('sean@rx931.com')
                && $mail->hasCc('seanphilipcruz@gmail.com')
                && $mail->build()->subject === 'A bug has been reported by user reporter@example.test';
        });
    }

    public function test_bug_report_api_requires_authentication(): void
    {
        $this->postJson('/api/bug-reports', [
            'title' => 'Cannot save this record',
            'description' => '<p>The request fails.</p>',
        ])->assertUnauthorized()->assertJsonPath('code', 'UNAUTHENTICATED');
    }
}

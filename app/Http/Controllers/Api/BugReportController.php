<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BugReportSubmitted;
use App\Models\BugReport;
use App\Support\RichTextSanitizer;
use App\Support\StationContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

final class BugReportController extends Controller
{
    public function __construct(
        private RichTextSanitizer $richText,
        private StationContext $stations
    ) {}

    public function store(Request $request): JsonResponse
    {
        $maximumFiles = (int) config('bug_reports.max_attachments', 5);
        $maximumKilobytes = (int) config('bug_reports.max_attachment_kilobytes', 8192);
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:6', 'max:255'],
            'description' => ['required', 'string', 'max:500000'],
            'page_url' => ['nullable', 'url', 'max:2048'],
            'screenshots' => ['nullable', 'array', 'max:'.$maximumFiles],
            'screenshots.*' => ['file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:'.$maximumKilobytes],
        ]);
        $description = $this->richText->sanitize($validated['description']);
        $screenshots = $request->file('screenshots', []);
        $hasDescription = trim(html_entity_decode(strip_tags($description))) !== '';
        if (! $hasDescription && $screenshots === []) {
            throw ValidationException::withMessages([
                'description' => 'Describe the problem or paste at least one screenshot.',
            ]);
        }

        $user = $request->user();
        abort_if($user === null, 401);
        $storedPaths = [];

        try {
            $report = DB::transaction(function () use ($description, $request, $screenshots, &$storedPaths, $user, $validated): BugReport {
                $report = BugReport::query()->create([
                    'title' => $validated['title'],
                    'description' => $description,
                    'page_url' => $validated['page_url'] ?? null,
                    'image' => '',
                    'employee_id' => $user->employee_id,
                    'reporter_email' => $user->email,
                    'location' => $this->stations->current($request),
                    'is_resolved' => false,
                ]);

                foreach ($screenshots as $index => $screenshot) {
                    $extension = strtolower($screenshot->guessExtension() ?: 'png');
                    $storedName = Str::uuid()->toString().'.'.$extension;
                    $storagePath = $screenshot->storeAs('bug-reports/'.$report->id, $storedName, 'local');
                    if (! is_string($storagePath)) {
                        throw new \RuntimeException('The screenshot could not be stored.');
                    }
                    $storedPaths[] = $storagePath;

                    DB::table('report_attachments')->insert([
                        'report_id' => $report->id,
                        'original_name' => Str::limit($screenshot->getClientOriginalName(), 255, ''),
                        'storage_path' => $storagePath,
                        'mime_type' => $screenshot->getMimeType() ?: 'application/octet-stream',
                        'size' => $screenshot->getSize(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($index === 0) {
                        $report->image = $storedName;
                    }
                }

                $report->save();

                return $report;
            });
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('local')->delete($path);
            }
            throw $exception;
        }

        $reviewUrl = route('bug-reports.show', ['bugReport' => $report->id]);
        $notificationSent = true;
        try {
            Mail::to((string) config('bug_reports.to'))
                ->cc((string) config('bug_reports.cc'))
                ->send(new BugReportSubmitted($report, $reviewUrl));
            $report->forceFill(['notification_sent_at' => now(), 'notification_failed_at' => null])->save();
        } catch (Throwable $exception) {
            $notificationSent = false;
            $report->forceFill(['notification_failed_at' => now()])->save();
            report($exception);
        }

        return $this->successResponse([
            'id' => $report->id,
            'review_url' => $reviewUrl,
            'notification_sent' => $notificationSent,
        ], $notificationSent
            ? 'The bug report was saved and the notification email was sent.'
            : 'The bug report was saved, but the notification email could not be delivered.', 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\BugReportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class BugReportReviewController extends Controller
{
    public function show(int $bugReport): Response
    {
        $report = BugReport::query()->findOrFail($bugReport);
        $attachments = BugReportAttachment::query()
            ->where('report_id', $report->id)
            ->orderBy('id')
            ->get()
            ->map(fn (BugReportAttachment $attachment): array => [
                'id' => $attachment->id,
                'name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'size' => $attachment->size,
                'url' => route('bug-reports.attachments.show', [
                    'bugReport' => $report->id,
                    'attachment' => $attachment->id,
                ]),
            ]);

        return Inertia::render('BugReports/Show', [
            'report' => [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'reporter_email' => $report->reporter_email,
                'location' => $report->location,
                'page_url' => $report->page_url,
                'is_resolved' => $report->is_resolved,
                'created_at' => $report->created_at?->toIso8601String(),
                'attachments' => $attachments,
            ],
        ]);
    }

    public function attachment(Request $request, int $bugReport, int $attachment): StreamedResponse
    {
        $record = BugReportAttachment::query()
            ->where('id', $attachment)
            ->where('report_id', $bugReport)
            ->firstOrFail();
        abort_unless(Storage::disk('local')->exists($record->storage_path), 404);

        return Storage::disk('local')->response(
            $record->storage_path,
            $record->original_name,
            ['Content-Type' => $record->mime_type],
            'inline'
        );
    }
}

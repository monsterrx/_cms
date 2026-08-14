<?php

namespace App\Mail;

use App\Models\BugReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class BugReportSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BugReport $bugReport,
        public string $reviewUrl
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('A bug has been reported by user '.$this->bugReport->reporter_email)
            ->markdown('emails.bug-report-submitted');
    }
}

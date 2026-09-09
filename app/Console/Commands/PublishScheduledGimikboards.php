<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublishScheduledGimikboards extends Command
{
    protected $signature = 'gimikboards:publish-scheduled';

    protected $description = 'Publish events whose scheduled publication time has arrived';

    public function handle(): int
    {
        $count = DB::table('gimikboards')->whereNull('deleted_at')->where('is_published', 0)
            ->whereNotNull('published_at')->where('published_at', '<=', now())
            ->update(['is_published' => 1, 'updated_at' => now()]);
        $this->info("Published {$count} events.");

        return self::SUCCESS;
    }
}

<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkspaceVisitRecorder
{
    public function record(?User $user, string $section, ?string $item = null): void
    {
        if ($user === null || ! Schema::hasTable('user_workspace_visits')) {
            return;
        }

        DB::table('user_workspace_visits')->upsert(
            [[
                'user_id' => $user->id,
                'section_slug' => $section,
                'item_slug' => $item ?? '',
                'last_visited_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]],
            ['user_id', 'section_slug', 'item_slug'],
            ['last_visited_at', 'updated_at'],
        );
    }
}

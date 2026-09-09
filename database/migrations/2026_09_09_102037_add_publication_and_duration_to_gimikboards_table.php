<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gimikboards', function (Blueprint $table): void {
            $table->string('event_duration')->nullable();
            $table->dateTime('published_at')->nullable();
        });
        DB::table('gimikboards')->where('is_published', 1)->update(['published_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('gimikboards', function (Blueprint $table): void {
            $table->dropColumn(['event_duration', 'published_at']);
        });
    }
};

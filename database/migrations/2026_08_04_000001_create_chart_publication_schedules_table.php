<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE charts MODIFY is_posted TINYINT(1) NULL DEFAULT NULL');
        }

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
            $table->index(['status', 'publish_at']);
            $table->index(['location', 'chart_date', 'chart_type'], 'chart_schedule_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_publication_schedules');
        if (DB::getDriverName() === 'mysql') {
            DB::table('charts')->whereNull('is_posted')->update(['is_posted' => 0]);
            DB::statement('ALTER TABLE charts MODIFY is_posted TINYINT(1) NOT NULL DEFAULT 0');
        }
    }
};

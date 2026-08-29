<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->mediumText('description');
                $table->string('page_url', 2048)->nullable();
                $table->string('image')->default('');
                $table->unsignedBigInteger('employee_id')->nullable()->index();
                $table->string('reporter_email')->nullable();
                $table->string('location', 20)->nullable()->default('mnl');
                $table->boolean('is_resolved')->default(false);
                $table->timestamp('notification_sent_at')->nullable();
                $table->timestamp('notification_failed_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        } else {
            $this->withLegacyDateCompatibility(function (): void {
                Schema::table('reports', function (Blueprint $table): void {
                    if (! Schema::hasColumn('reports', 'page_url')) {
                        $table->string('page_url', 2048)->nullable()->after('description');
                    }
                    if (! Schema::hasColumn('reports', 'reporter_email')) {
                        $table->string('reporter_email')->nullable()->after('employee_id');
                    }
                    if (! Schema::hasColumn('reports', 'notification_sent_at')) {
                        $table->timestamp('notification_sent_at')->nullable()->after('is_resolved');
                    }
                    if (! Schema::hasColumn('reports', 'notification_failed_at')) {
                        $table->timestamp('notification_failed_at')->nullable()->after('notification_sent_at');
                    }
                });
            });
        }

        if (! Schema::hasTable('report_attachments')) {
            Schema::create('report_attachments', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('report_id')->index();
                $table->string('original_name');
                $table->string('storage_path');
                $table->string('mime_type', 100);
                $table->unsignedBigInteger('size');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('report_attachments');

        if (! Schema::hasTable('reports')) {
            return;
        }

        $columns = array_values(array_filter([
            Schema::hasColumn('reports', 'page_url') ? 'page_url' : null,
            Schema::hasColumn('reports', 'reporter_email') ? 'reporter_email' : null,
            Schema::hasColumn('reports', 'notification_sent_at') ? 'notification_sent_at' : null,
            Schema::hasColumn('reports', 'notification_failed_at') ? 'notification_failed_at' : null,
        ]));

        if ($columns !== []) {
            $this->withLegacyDateCompatibility(function () use ($columns): void {
                Schema::table('reports', static function (Blueprint $table) use ($columns): void {
                    $table->dropColumn($columns);
                });
            });
        }
    }

    private function withLegacyDateCompatibility(callable $operation): void
    {
        if (DB::getDriverName() !== 'mysql') {
            $operation();

            return;
        }

        $sqlMode = (string) DB::selectOne('SELECT @@SESSION.sql_mode AS sql_mode')->sql_mode;
        $relaxedMode = implode(',', array_filter(
            explode(',', $sqlMode),
            static fn (string $mode): bool => ! in_array($mode, ['NO_ZERO_DATE', 'NO_ZERO_IN_DATE'], true)
        ));
        DB::unprepared('SET SESSION sql_mode = '.DB::getPdo()->quote($relaxedMode));

        try {
            $operation();
        } finally {
            DB::unprepared('SET SESSION sql_mode = '.DB::getPdo()->quote($sqlMode));
        }
    }
};

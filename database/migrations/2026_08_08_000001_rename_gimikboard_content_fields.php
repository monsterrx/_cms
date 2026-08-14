<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gimikboards')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            if (Schema::hasColumn('gimikboards', 'title') && ! Schema::hasColumn('gimikboards', 'name')) {
                DB::statement('ALTER TABLE gimikboards RENAME COLUMN title TO name');
            }
            if (Schema::hasColumn('gimikboards', 'sub_description')) {
                DB::statement('ALTER TABLE gimikboards RENAME COLUMN sub_description TO title');
            }

            return;
        }

        $this->withLegacyDateCompatibility(function (): void {
            if (Schema::hasColumn('gimikboards', 'title') && ! Schema::hasColumn('gimikboards', 'name')) {
                DB::statement('ALTER TABLE `gimikboards` CHANGE `title` `name` VARCHAR(255) NOT NULL');
            }

            if (Schema::hasColumn('gimikboards', 'sub_description')) {
                DB::statement('ALTER TABLE `gimikboards` CHANGE `sub_description` `title` LONGTEXT NULL AFTER `end_date`');
            }

            if (Schema::hasColumn('gimikboards', 'description')) {
                DB::statement('ALTER TABLE `gimikboards` MODIFY `description` LONGTEXT NULL AFTER `title`');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('gimikboards')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            if (Schema::hasColumn('gimikboards', 'title') && ! Schema::hasColumn('gimikboards', 'sub_description')) {
                DB::statement('ALTER TABLE gimikboards RENAME COLUMN title TO sub_description');
            }
            if (Schema::hasColumn('gimikboards', 'name') && ! Schema::hasColumn('gimikboards', 'title')) {
                DB::statement('ALTER TABLE gimikboards RENAME COLUMN name TO title');
            }

            return;
        }

        $this->withLegacyDateCompatibility(function (): void {
            if (Schema::hasColumn('gimikboards', 'title') && ! Schema::hasColumn('gimikboards', 'sub_description')) {
                DB::statement('ALTER TABLE `gimikboards` CHANGE `title` `sub_description` LONGTEXT NULL AFTER `location`');
            }

            if (Schema::hasColumn('gimikboards', 'description')) {
                DB::statement('ALTER TABLE `gimikboards` MODIFY `description` LONGTEXT NULL AFTER `end_date`');
            }

            if (Schema::hasColumn('gimikboards', 'name') && ! Schema::hasColumn('gimikboards', 'title')) {
                DB::statement('ALTER TABLE `gimikboards` CHANGE `name` `title` VARCHAR(255) NOT NULL AFTER `id`');
            }
        });
    }

    private function withLegacyDateCompatibility(callable $operation): void
    {
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

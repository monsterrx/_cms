<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('designation_grants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('designation_id');
            $table->string('section_slug');
            $table->string('item_slug');
            $table->boolean('can_write')->default(false);
            $table->timestamps();

            $table->unique(['designation_id', 'section_slug', 'item_slug']);
        });

        $defaults = [
            3 => ['staff:jocks', 'staff:radio1-batches', 'staff:student-jocks', 'music:artists', 'music:albums', 'music:songs', 'music:genres', 'music:indieground-artists', 'music:indieground-featured', 'digital-content-programs:articles', 'digital-content-programs:categories', 'digital-content-programs:shows', 'digital-content-programs:timeslots', 'digital-content-programs:podcasts', 'promos:giveaways', 'promos:contestants'],
            4 => ['staff:jocks', 'digital-content-programs:graphics-artist', 'digital-content-programs:wallpapers', 'digital-content-programs:shows', 'events-scholarship:schools', 'events-scholarship:gimik-board'],
            5 => ['staff:jocks'],
            6 => ['utilities:messages'],
            7 => ['music:artists', 'music:albums', 'music:songs', 'music:genres', 'music:station-chart', 'music:dropouts'],
            8 => ['staff:jocks', 'digital-content-programs:timeslots', 'digital-content-programs:shows', 'digital-content-programs:podcasts'],
            9 => ['staff:staffs', 'staff:student-jocks', 'staff:awards', 'music:artists', 'music:albums', 'music:songs', 'music:genres', 'music:station-chart', 'music:dropouts', 'events-scholarship:schools', 'events-scholarship:gimik-board', 'events-scholarship:scholar-batches', 'events-scholarship:students', 'events-scholarship:sponsors'],
        ];
        $writeLevels = [3, 4, 5, 7, 8];
        $designations = DB::table('designations')->whereNull('deleted_at')->get(['id', 'level']);

        foreach ($designations as $designation) {
            foreach ($defaults[(int) $designation->level] ?? [] as $grant) {
                [$section, $item] = explode(':', $grant, 2);
                DB::table('designation_grants')->insert([
                    'designation_id' => $designation->id,
                    'section_slug' => $section,
                    'item_slug' => $item,
                    'can_write' => in_array((int) $designation->level, $writeLevels, true) && $item !== 'messages',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designation_grants');
    }
};

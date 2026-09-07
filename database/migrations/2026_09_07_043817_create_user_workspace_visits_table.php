<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_workspace_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('section_slug');
            $table->string('item_slug')->default('');
            $table->timestamp('last_visited_at');
            $table->timestamps();

            $table->unique(['user_id', 'section_slug', 'item_slug']);
            $table->index(['user_id', 'last_visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_workspace_visits');
    }
};

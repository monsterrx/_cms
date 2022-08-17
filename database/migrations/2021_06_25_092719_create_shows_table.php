<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('slug_string');
            $table->string('title');
            $table->string('front_description');
            $table->text('description');
            $table->string('icon');
            $table->string('header_image')->nullable()->default('default-banner.png');
            $table->string('background_image')->nullable()->default('default.png');
            $table->boolean('is_special')->default(1);
            $table->boolean('is_active')->default(1);
            $table->string('location')->default('mnl');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shows');
    }
}

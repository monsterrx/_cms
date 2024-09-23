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
            $table->string('title');
            $table->string('front_description');
            $table->text('description');
            $table->string('slug_string');
            $table->boolean('is_special');
            $table->boolean('is_active');
            $table->string('location');
            $table->string('icon')->default('default.png');
            $table->string('background_image')->default('default.png');
            $table->string('header_image')->default('default-banner.png');
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

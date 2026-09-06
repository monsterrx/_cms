<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedIndiegroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_indiegrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indieground_id');
            $table->string('header');
            $table->text('content');
            $table->string('month');
            $table->year('year');
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
        Schema::dropIfExists('featured_indiegrounds');
    }
}

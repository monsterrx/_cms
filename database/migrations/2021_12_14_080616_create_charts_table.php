<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_id');
            $table->integer('position');
            $table->integer('last_position')->default(0);
            $table->date('dated');
            $table->boolean('re_entry');
            $table->boolean('daily');
            $table->boolean('local');
            $table->boolean('is_dropped')->default(0);
            $table->string('location')->default('mnl');
            $table->integer('votes')->default(0);
            $table->integer('last_results')->default(0);
            $table->integer('phone_votes')->default(0);
            $table->integer('social_votes')->default(0);
            $table->integer('online_votes')->default(0);
            $table->date('voted_at')->nullable();
            $table->boolean('is_posted')->default(0);
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
        Schema::dropIfExists('charts');
    }
}

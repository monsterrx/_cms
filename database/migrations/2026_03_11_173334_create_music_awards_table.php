<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMusicAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_awards', function (Blueprint $table) {
            $table->id();
            $table->integer('release');
            $table->string('award_name');
            $table->unsignedBigInteger('artist_id');
            $table->unsignedBigInteger('album_id');
            $table->unsignedBigInteger('song_id');
            $table->string('image');
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
        Schema::dropIfExists('music_awards');
    }
}

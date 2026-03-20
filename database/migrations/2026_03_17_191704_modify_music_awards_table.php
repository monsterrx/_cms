<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMusicAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_awards', function (Blueprint $table) {
            $table->unsignedBigInteger('artist_id')->nullable()->change();
            $table->unsignedBigInteger('album_id')->nullable()->change();
            $table->unsignedBigInteger('song_id')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_awards', function (Blueprint $table) {
            //
        });
    }
}

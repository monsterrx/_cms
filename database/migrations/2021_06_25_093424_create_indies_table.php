<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indiegrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->text('introduction');
            $table->string('image')->nullable()->default('default.png');
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
        Schema::dropIfExists('indies');
    }
}

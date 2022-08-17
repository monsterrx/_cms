<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tallies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chart_id');
            $table->integer('result')->nullable()->default(0);;
            $table->integer('last_result')->nullable()->default(0);
            $table->dateTime('dated');
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
        Schema::dropIfExists('tallies');
    }
}

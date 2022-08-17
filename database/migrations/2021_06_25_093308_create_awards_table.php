<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jock_id')->nullable();
            $table->unsignedBigInteger('show_id')->nullable();
            $table->string('name');
            $table->string('title');
            $table->string('description');
            $table->year('year');
            $table->boolean('is_special')->default(0);
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
        Schema::dropIfExists('awards');
    }
}

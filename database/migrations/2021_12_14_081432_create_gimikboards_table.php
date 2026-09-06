<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGimikboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gimikboards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->text('sub_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('school_id');
            $table->boolean('is_published');
            $table->string('location');
            $table->string('image')->default('default.png');
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
        Schema::dropIfExists('gimikboards');
    }
}

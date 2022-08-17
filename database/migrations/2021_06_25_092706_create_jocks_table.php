<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('slug_string');
            $table->string('name');
            $table->string('moniker')->nullable();
            $table->text('description')->nullable();
            $table->string('profile_image')->nullable()->default('default.png');;
            $table->string('background_image')->nullable()->default('default-banner-sm.png');;
            $table->string('main_image')->nullable()->default('default.png');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('jocks');
    }
}

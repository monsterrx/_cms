<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentJockStudentJockBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_jock_student_jock_batch', function (Blueprint $table) {
            $table->unsignedBigInteger('student_jock_id');
            $table->unsignedBigInteger('student_jock_batch_id');
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
        Schema::dropIfExists('student_jock_student_jock_batch');
    }
}

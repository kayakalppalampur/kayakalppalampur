<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespiratoryExaminations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respiratory_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->integer('cough');
            $table->string('cough_doctor')->nullable();
            $table->integer('fever');
            $table->string('fever_doctor')->nullable();
            $table->integer('sinusitis');
            $table->string('sinusitis_doctor')->nullable();
            $table->integer('chest_pain');
            $table->string('chest_pain_doctor')->nullable();
            $table->integer('wheeze');
            $table->string('wheeze_doctor')->nullable();
            $table->integer('hoarsness');
            $table->string('hoarsness_doctor')->nullable();
            $table->string('doctor_details')->nullable();
            $table->integer('booking_id')->nullable();
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
        Schema::dropIfExists('respiratory_examinations');
    }
}

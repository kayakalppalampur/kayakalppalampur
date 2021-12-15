<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeurologicalExaminations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neurological_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->integer('headache');
            $table->string('headache_doctor')->nullable();
            $table->integer('vision_hearing');
            $table->string('vision_hearing_doctor')->nullable();
            $table->integer('pain');
            $table->string('pain_doctor')->nullable();
            $table->integer('numbness');
            $table->string('numbness_doctor')->nullable();
            $table->integer('weakness');
            $table->string('weakness_doctor')->nullable();
            $table->integer('abnormal_movements');
            $table->string('abnormal_movements_doctor')->nullable();
            $table->integer('fits');
            $table->string('fits_doctor')->nullable();
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
        Schema::dropIfExists('neurological_examinations');
    }
}

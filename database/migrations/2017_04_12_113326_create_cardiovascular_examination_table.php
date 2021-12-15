<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardiovascularExaminationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cardiovascular_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->integer('chest_pain');
            $table->string('chest_pain_doctor')->nullable();
            $table->integer('dyspnoea');
            $table->string('dyspnoea_doctor')->nullable();
            $table->integer('palpitations');
            $table->string('palpitations_doctor')->nullable();
            $table->integer('dizziness');
            $table->string('dizziness_doctor')->nullable();
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
        Schema::dropIfExists('cardiovascular_examinations');
    }
}

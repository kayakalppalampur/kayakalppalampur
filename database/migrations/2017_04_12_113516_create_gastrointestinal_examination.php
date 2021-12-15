<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGastrointestinalExamination extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastrointestinal_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->integer('abdominal_pain');
            $table->string('abdominal_pain_doctor')->nullable();
            $table->integer('nausea');
            $table->string('nausea_doctor')->nullable();
            $table->integer('dysphagia');
            $table->string('dysphagia_doctor')->nullable();
            $table->integer('indigestion');
            $table->string('indigestion_doctor')->nullable();
            $table->integer('change_in_bowel_habits');
            $table->string('change_in_bowel_habits_doctor')->nullable();
            $table->integer('diarrhoea_constipation');
            $table->string('diarrhoea_constipation_doctor')->nullable();
            $table->integer('rectal_bleeding');
            $table->string('rectal_bleeding_doctor')->nullable();
            $table->integer('weight_change');
            $table->string('weight_change_doctor')->nullable();
            $table->integer('dark_urine');
            $table->string('dark_urine_doctor')->nullable();
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
        Schema::dropIfExists('gastrointestinal_examinations');
    }
}

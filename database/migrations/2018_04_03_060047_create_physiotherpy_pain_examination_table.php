<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherpyPainExaminationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_pain_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('muscle_pain')->nullable();
            $table->string('muscle_pain_comment')->nullable();
            $table->integer('back_pain')->nullable();
            $table->string('back_pain_comment')->nullable();
            $table->integer('knee_pain')->nullable();
            $table->string('knee_pain_comment')->nullable();
            $table->integer('joint_pain')->nullable();
            $table->string('joint_pain_comment')->nullable();
            $table->integer('spinal_injuries')->nullable();
            $table->string('spinal_injuries_comment')->nullable();
            $table->integer('joint_stiffness')->nullable();
            $table->string('joint_stiffness_comment')->nullable();
            $table->integer('side')->nullable();
            $table->string('onset_of_symptoms')->nullable();
            $table->integer('priorities_injuries_to_affected_area')->nullable();
            $table->text('priorities_injuries_to_affected_area_comment')->nullable();
            $table->integer('booking_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('physiotherapy_pain_examinations');
    }
}

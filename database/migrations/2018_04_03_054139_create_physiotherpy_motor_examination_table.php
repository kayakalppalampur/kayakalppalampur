<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherpyMotorExaminationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_motor_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rom_of_joint')->nullable();
            $table->integer('rom_of_joint_type')->nullable();
            $table->string('flexion_values')->nullable();
            $table->integer('muscle_power_grade')->nullable();
            $table->string('muscle_power_grade_comment')->nullable();
            $table->integer('muscle_power_tone')->nullable();
            $table->string('muscle_power_tone_comment')->nullable();
            $table->integer('deep_reflexes')->nullable();
            $table->string('deep_reflexes_comment')->nullable();
            $table->integer('superficial_reflexes')->nullable();
            $table->string('superficial_reflexes_comment')->nullable();
            $table->integer('bower_and_bladder')->nullable();
            $table->string('bower_and_bladder_comment')->nullable();
            $table->string('specific_test')->nullable();
            $table->string('provisional_diagonosis')->nullable();
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
        Schema::dropIfExists('physiotherapy_motor_examinations');
    }
}

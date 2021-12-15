<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChnageColumnDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physiotherapy_systemic_examinations', function (Blueprint $table) {
            $table->string('body_built')->change();
            $table->string('gait')->change();
            $table->string('posture')->change();
            $table->string('edema')->change();
        });
        Schema::table('physiotherapy_sensory_examinations', function (Blueprint $table) {
            $table->string('superficial_sensation')->change();
            $table->string('deep_sensation')->change();
            $table->string('hot_or_cold_sensation')->change();
        });
        Schema::table('physiotherapy_motor_examinations', function (Blueprint $table) {
            $table->string('rom_of_joint')->change();
            $table->string('rom_of_joint_type')->change();
            $table->string('muscle_power_grade')->change();
            $table->string('muscle_power_tone')->change();
            $table->string('deep_reflexes')->change();
            $table->string('superficial_reflexes')->change();
            $table->string('bower_and_bladder')->change();
        });

        Schema::table('physiotherapy_pain_assesments', function (Blueprint $table) {
            $table->string('type_of_pain')->change();
            $table->string('nature_of_pain')->change();
            $table->string('symptoms_are_worse')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physiotherapy_systemic_examinations', function (Blueprint $table) {
            $table->integer('body_built')->change();
            $table->integer('gait')->change();
            $table->integer('posture')->change();
            $table->integer('edema')->change();
        });
        Schema::table('physiotherapy_sensory_examinations', function (Blueprint $table) {
            $table->integer('superficial_sensation')->change();
            $table->integer('deep_sensation')->change();
            $table->integer('hot_or_cold_sensation')->change();
        });
        Schema::table('physiotherapy_motor_examinations', function (Blueprint $table) {
            $table->integer('rom_of_joint')->change();
            $table->integer('rom_of_joint_type')->change();
            $table->integer('muscle_power_grade')->change();
            $table->integer('muscle_power_tone')->change();
            $table->integer('deep_reflexes')->change();
            $table->integer('superficial_reflexes')->change();
            $table->integer('bower_and_bladder')->change();
        });
        Schema::table('physiotherapy_pain_assesments', function (Blueprint $table) {
            $table->integer('type_of_pain')->change();
            $table->integer('nature_of_pain')->change();
            $table->integer('symptoms_are_worse')->change();


        });
    }
}

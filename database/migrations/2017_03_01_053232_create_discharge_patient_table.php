<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDischargePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discharge_patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('token_id');
            $table->date('date_of_arrival');
            $table->date('date_of_discharge');
            $table->string('diagnosis')->nullable();
            $table->text('discharge_summary');
            $table->text('investigation_report')->nullable();
            $table->integer('vital_data_id');
            $table->text('summary')->nullable();
            $table->text('things_to_avoid')->nullable();
            $table->text('follow_up_advice')->nullable();
            $table->integer('diet_plan_duration')->nullable();
            $table->integer('diet_plan_id')->nullable();
            $table->string('followup_id')->nullable();
            $table->integer('status')->nullable();
            $table->integer('doctor_id');
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
        Schema::dropIfExists('discharge_patients');
    }
}

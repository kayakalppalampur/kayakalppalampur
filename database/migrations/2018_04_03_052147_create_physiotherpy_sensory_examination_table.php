<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherpySensoryExaminationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_sensory_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('superficial_sensation')->nullable();
            $table->string('superficial_sensation_comment')->nullable();
            $table->integer('deep_sensation')->nullable();
            $table->string('deep_sensation_comment')->nullable();
            $table->integer('hot_or_cold_sensation')->nullable();
            $table->string('hot_or_cold_sensation_comment')->nullable();
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
        Schema::dropIfExists('physiotherapy_sensory_examinations');
    }
}

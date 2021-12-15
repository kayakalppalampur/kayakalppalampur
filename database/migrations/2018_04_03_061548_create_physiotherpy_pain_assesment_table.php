<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherpyPainAssesmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_pain_assesments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pain_at_rest')->nullable();
            $table->integer('pain_with_activity')->nullable();
            $table->integer('pain_at_night')->nullable();
            $table->string('aggregation_factor')->nullable();
            $table->string('relieving_factor')->nullable();
            $table->integer('type_of_pain')->nullable();
            $table->integer('nature_of_pain')->nullable();
            $table->integer('symptoms_are_worse')->nullable();
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
        Schema::dropIfExists('physiotherapy_pain_assesments');
    }
}

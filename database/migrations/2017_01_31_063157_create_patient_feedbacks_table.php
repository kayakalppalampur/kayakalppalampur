<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string("question_id")->nullable();
            $table->string("rate")->nullable();
            $table->text("feedback")->nullable();
            $table->integer("user_id");
            $table->integer("doctor_id")->nullable();
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
        Schema::dropIfExists('patient_feedbacks');
    }
}

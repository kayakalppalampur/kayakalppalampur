<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDiagnosisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_diagnosis', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->text('others')->nullable();
            $table->date('date');
            $table->integer('booking_id');
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->integer('status')->nullable();
            $table->integer('type')->nullable();
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
        Schema::dropIfExists('patient_diagnosis');
    }
}

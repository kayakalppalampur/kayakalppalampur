<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_lab_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lab_name');
            $table->string('date');
            $table->string('address')->nullable();
            $table->integer('test_id');
            $table->string('note')->nullable();
            $table->integer('department_id');
            $table->integer('created_by');
            $table->integer('patient_id');
            $table->integer('status');
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
        Schema::dropIfExists('patient_lab_tests');
    }
}

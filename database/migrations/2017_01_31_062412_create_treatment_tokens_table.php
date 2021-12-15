<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_no');
            $table->integer('patient_id');
            $table->date('treatment_date');
            $table->date('expiry_date');
            $table->integer('patient_detail_id');
            $table->integer('department_id');
            $table->string('feedback');
            $table->string('doctor_remark');
            $table->integer('created_by');
            $table->string('note');
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
        Schema::dropIfExists('treatment_tokens');
    }
}

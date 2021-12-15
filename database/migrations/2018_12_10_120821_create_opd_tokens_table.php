<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpdTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opd_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('patient_id');
            $table->integer('department_id');
            $table->integer('doctor_id');
            $table->integer('date');
            $table->longText('complaints');
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('opd_tokens');
    }
}

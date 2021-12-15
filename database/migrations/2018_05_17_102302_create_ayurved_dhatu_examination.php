<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAyurvedDhatuExamination extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayurved_dhatu_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->string('ras_growth')->nullable();
            $table->string('ras_decay')->nullable();
            $table->string('rakht_growth')->nullable();
            $table->string('rakht_decay')->nullable();
            $table->string('maans_growth')->nullable();
            $table->string('maans_decay')->nullable();
            $table->string('med_growth')->nullable();
            $table->string('med_decay')->nullable();
            $table->string('asthi_growth')->nullable();
            $table->string('asthi_decay')->nullable();
            $table->string('majja_growth')->nullable();
            $table->string('majja_decay')->nullable();
            $table->string('shukra_growth')->nullable();
            $table->string('shukra_decay')->nullable();
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
        Schema::dropIfExists('ayurved_dhatu_examinations');
    }
}

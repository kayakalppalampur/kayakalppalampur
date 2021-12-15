<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAyurvedDoshExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayurved_dosh_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->string('vat_dosh_growth')->nullable();
            $table->string('vat_dosh_decay')->nullable();
            $table->string('pitt_dosh_growth')->nullable();
            $table->string('pitt_dosh_decay')->nullable();
            $table->string('kaph_dosh_growth')->nullable();
            $table->string('kaph_dosh_decay')->nullable();
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
        Schema::dropIfExists('ayurved_dosh_examinations');
    }
}


<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkinExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skin_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('booking_id')->nullable();
            $table->string('skin');
            $table->string('eye_ent');
            $table->integer('status');
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
        Schema::dropIfExists('skin_examinations');
    }
}

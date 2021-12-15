<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysicalExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->string('hair')->nullable();
            $table->string('forehead')->nullable();
            $table->string('eyes')->nullable();
            $table->string('nails')->nullable();
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
        Schema::dropIfExists('physical_examinations');
    }
}

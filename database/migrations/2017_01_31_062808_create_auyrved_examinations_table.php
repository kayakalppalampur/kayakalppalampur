<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuyrvedExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayurved_ashtvidh_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('status');
            $table->string('pulse')->nullable();
            $table->string('pulse_issue')->nullable();
            $table->string('pulse_comment')->nullable();
            $table->string('faecal_matter')->nullable();
            $table->string('faecal_matter_speed_days')->nullable();
            $table->string('faecal_matter_comment')->nullable();
            $table->string('faecal_matter_liquid')->nullable();
            $table->string('faecal_matter_liquid_speed_days')->nullable();
            $table->string('faecal_matter_liquid_speed_nights')->nullable();
            $table->string('faecal_matter_liquid_comment')->nullable();
            $table->string('skin')->nullable();
            $table->string('skin_comment')->nullable();
            $table->string('eyes')->nullable();
            $table->string('eyes_comment')->nullable();
            $table->string('tongue')->nullable();
            $table->string('tongue_comment')->nullable();
            $table->string('speech')->nullable();
            $table->string('speech_comment')->nullable();
            $table->string('body_build')->nullable();
            $table->string('body_build_comment')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('ayurved_ashtvidh_examinations');
    }
}

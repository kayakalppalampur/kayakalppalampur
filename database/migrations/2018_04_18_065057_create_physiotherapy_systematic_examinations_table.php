<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherapySystematicExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_systemic_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('body_built')->nullable();
            $table->integer('gait')->nullable();
            $table->integer('posture')->nullable();
            $table->string('posture_comment')->nullable();
            $table->integer('deformity')->nullable();
            $table->string('deformity_comment')->nullable();
            $table->integer('tenderness')->nullable();
            $table->string('tenderness_comment')->nullable();
            $table->integer('warmth')->nullable();
            $table->string('warmth_comment')->nullable();
            $table->integer('swelling')->nullable();
            $table->string('swelling_comment')->nullable();
            $table->integer('creiptus')->nullable();
            $table->string('creiptus_comment')->nullable();
            $table->integer('muscle_spasm')->nullable();
            $table->string('muscle_spasm_comment')->nullable();
            $table->integer('muscle_tightness')->nullable();
            $table->string('muscle_tightness_comment')->nullable();
            $table->integer('edema')->nullable();
            $table->integer('booking_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('physiotherapy_systemic_examinations');
    }
}

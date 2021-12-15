<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenitourinaryExaminations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genitourinary_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->integer('fever');
            $table->string('fever_doctor')->nullable();
            $table->integer('loin_pain');
            $table->string('loin_pain_doctor')->nullable();
            $table->integer('dysuria');
            $table->string('dysuria_doctor')->nullable();
            $table->integer('urethral_discharge');
            $table->string('urethral_discharge_doctor')->nullable();
            $table->integer('painful_sexual_intercourse');
            $table->string('painful_sexual_intercourse_doctor')->nullable();
            $table->integer('menarche');
            $table->string('menarche_doctor')->nullable();
            $table->integer('menopause');
            $table->string('menopause_doctor')->nullable();
            $table->integer('length_of_periods');
            $table->string('length_of_periods_doctor')->nullable();
            $table->integer('amount_pain');
            $table->string('amount_pain_doctor')->nullable();
            $table->date('LMP')->nullable();
            $table->string('LMP_doctor')->nullable();
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
        Schema::dropIfExists('genitourinary_examinations');
    }
}

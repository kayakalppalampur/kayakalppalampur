<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsultationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('charges');
            $table->string('foreign_charges')->nullable();
            $table->integer('type')->nullable();
            $table->integer('status')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('consultation_charges');
    }
}

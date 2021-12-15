<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDietChartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_chart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("patient_id");
            $table->date("start_date");
            $table->integer("repeats");
            $table->integer("status");
            $table->text("notes")->nullable();
            $table->date("end_date");
            $table->integer("created_by");
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
        Schema::dropIfExists('diet_chart');
    }
}

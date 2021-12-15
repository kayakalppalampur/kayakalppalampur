<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentDischargeBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_discharge_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('department_id');
            $table->text('summary')->nullable();
            $table->text('things_to_avoid')->nullable();
            $table->text('follow_up_advice')->nullable();
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
        Schema::dropIfExists('department_discharge_bookings');
    }
}

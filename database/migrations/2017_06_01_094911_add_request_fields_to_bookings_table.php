<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('building_id')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('is_confirmed')->nullable();
            $table->integer('patient_type')->nullable();
            $table->string('booking_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('building_id');
            $table->dropColumn('floor_number');
            $table->dropColumn('is_confirmed');
            $table->dropColumn('patient_type');
            $table->dropColumn('booking_id');
        });
    }
}


<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingIdDischargePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discharge_patients', function (Blueprint $table) {
            if(!Schema::hascolumn('discharge_patients', 'booking_id')) {
                $table->integer('booking_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discharge_patients', function (Blueprint $table) {
            if(Schema::hascolumn('discharge_patients', 'booking_id')){
                $table->dropColumn('booking_id');
            }
        });
    }
}

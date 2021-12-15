<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if(!Schema::hasColumn('bookings', 'child_count')) {
                $table->integer('child_count')->nullable();
            }
            if(!Schema::hasColumn('bookings', 'driver_count')) {
                $table->integer('driver_count')->nullable();
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
        Schema::table('bookings', function (Blueprint $table) {
            if(Schema::hasColumn('bookings', 'child_count')) {
                $table->dropColumn('child_count');
            }
             if(Schema::hasColumn('bookings', 'driver_count')) {
                $table->dropColumn('driver_count');
            }
        });
    }
}

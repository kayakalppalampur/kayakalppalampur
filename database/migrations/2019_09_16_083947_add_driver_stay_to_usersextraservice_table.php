<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDriverStayToUsersextraserviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_extra_services', function (Blueprint $table) {
            if(!Schema::hasColumn('user_extra_services', 'driver_stay')) {
                $table->integer('driver_stay')->nullable();
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
         Schema::table('user_extra_services', function (Blueprint $table) {
            if(Schema::hasColumn('user_extra_services', 'driver_stay')) {
                $table->dropColumn('driver_stay');
            }
        });
    }
}

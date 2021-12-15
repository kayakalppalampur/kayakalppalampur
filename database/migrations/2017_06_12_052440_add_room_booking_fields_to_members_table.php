<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoomBookingFieldsToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->integer('room_id')->nullable();
            $table->integer('bed_number')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('room_id');
            $table->dropColumn('bed_number');
            $table->dropColumn('check_in_date');
            $table->dropColumn('check_out_date');
        });
    }
}

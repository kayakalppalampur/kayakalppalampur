<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->integer('is_child')->nullable();
            $table->integer('is_driver')->nullable();
            $table->integer('building_id')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('booking_type');

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
            $table->dropColumn('is_child');
            $table->dropColumn('is_driver');
            $table->dropColumn('building_id');
            $table->dropColumn('floor_number');
            $table->dropColumn('booking_type');
        });
    }
}

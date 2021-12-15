<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangingTypeColumnCommentsOnBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('type')->comment('1 => Cottage & Deluxe, 2 => Deluxe Double Bed, 3 => Double Bed Room, 4 => Dormitory')->change();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('type')->comment('1 => CD, 2 => C, 3 => DDB, 4 => DB, 5 => DR')->change();
        });
        Schema::table('beds', function (Blueprint $table) {
            $table->integer('type')->comment('1 => Normal, 2 => Extra')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

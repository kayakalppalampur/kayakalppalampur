<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('room_number');
            $table->integer('room_type_id')->unsigned();
            $table->integer('gender')->unsigned();
            $table->integer('building_id')->unsigned();
            $table->integer('floor_number')->unsigned();
            $table->string('bed_type',7);
            $table->integer('bed_count')->unsigned();
            $table->unique(array('building_id', 'room_number'));
            $table->integer('status')->comment('1 => Enable, 2 => Disable')->default(1);
            $table->string('services');
            /*$table->integer('is_blocked');
            $table->string('bed_price');
            $table->string('room_price');*/
            $table->integer('type')->nullable();
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
        Schema::drop('rooms');
    }
}

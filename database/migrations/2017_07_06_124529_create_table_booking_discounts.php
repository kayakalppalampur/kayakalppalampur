<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBookingDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('discount_id')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('basic_amount')->nullable();
            $table->integer('user_id');
            $table->integer('created_by');
            $table->integer('status')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('booking_discounts');
    }
}

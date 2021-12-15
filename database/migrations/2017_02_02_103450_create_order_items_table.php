<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('item_id')->comment("booking_id, treatment_id, service_id, user_id");
            $table->float('amount');
            $table->integer('discount_id')->unsigned()->nullable();
            $table->integer('additional_charge_percentage')->nullable();
            $table->integer('payable_amount');
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->tinyInteger('status')->comment('1=>paid,2=>pending');
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
        Schema::dropIfExists('order_items');
    }
}

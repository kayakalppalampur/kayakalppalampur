<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemQuantityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_quantity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->integer('qty');
            $table->integer('action');
            $table->integer('user_id');

            $table->integer('item_request_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('stock_item_requests', function (Blueprint $table) {
            $table->dateTime('approved_date')->nullable();
            $table->integer('approved_qty')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_quantity_logs');

        Schema::table('stock_item_requests', function (Blueprint $table) {
            $table->dropColumn('approved_date');
            $table->dropColumn('approved_qty');
        });
    }
}

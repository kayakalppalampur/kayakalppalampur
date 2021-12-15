<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitchenItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitchen_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('type')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('type')->nullable();
            $table->string('price')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('kitchen_items');
    }
}

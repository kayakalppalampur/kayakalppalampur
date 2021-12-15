<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDietDailyStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_daily_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("diet_id");
            $table->date("date");
            $table->string("is_breakfast")->nullable();
            $table->string("is_lunch")->nullable();
            $table->string("is_post_lunch")->nullable();
            $table->string("is_dinner")->nullable();
            $table->string("is_special")->nullable();
            $table->string("status");
            $table->string("created_by");
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
        Schema::dropIfExists('diet_daily_status');
    }
}

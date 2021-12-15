<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRomSubCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rom_sub_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sub_category')->nullable();
            $table->string('rom_joint_id')->nullable();
            $table->string('normal_rom')->nullable();
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
        Schema::dropIfExists('rom_sub_category');
    }
}

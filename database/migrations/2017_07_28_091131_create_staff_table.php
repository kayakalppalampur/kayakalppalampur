<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('marital_status')->nullable();
            $table->text('address')->nullable();
            $table->string('department')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_email')->nullable();
            $table->integer('created_by');
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('staff');
    }
}

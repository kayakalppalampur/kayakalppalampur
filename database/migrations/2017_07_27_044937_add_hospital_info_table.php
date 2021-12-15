<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHospitalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hospital_name');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('phone_no')->nullable();
            $table->integer('mobile_no')->nullable();
            $table->integer('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('hospital_info');
    }
}

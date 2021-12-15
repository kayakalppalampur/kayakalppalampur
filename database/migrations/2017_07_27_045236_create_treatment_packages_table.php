<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('package_name');
            $table->string('duration');
            $table->string('price');
            $table->integer('type');
            $table->integer('status')->nullable();
            $table->integer('department_id');
            $table->integer('created_by');
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
        Schema::dropIfExists('treatment_packages');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPhysicalExaminationsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->string('heart_rate')->nullable();
            $table->string('anaemia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->dropColumn('heart_rate');
            $table->dropColumn('anaemia');
        });
    }
}

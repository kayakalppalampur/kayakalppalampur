<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToNeaurologicalExaminationsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('neurological_examinations', function (Blueprint $table) {
            $table->string('eye_ent')->nullable();
            $table->string('skin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('neurological_examinations', function (Blueprint $table) {
            $table->dropColumn('eye_ent');
            $table->dropColumn('skin');
        });
    }
}

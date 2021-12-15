<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRogNidanInDhatuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ayurved_dhatu_examinations', function (Blueprint $table) {
            $table->string('rog_nidan')->after('shukra_decay')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ayurved_dhatu_examinations', function (Blueprint $table) {
            //
            $table->dropColumn('rog_nidan');


        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferanceNumberToOpdTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opd_tokens', function (Blueprint $table) {
            $table->date('date')->change();
            $table->integer('reference_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opd_tokens', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
}

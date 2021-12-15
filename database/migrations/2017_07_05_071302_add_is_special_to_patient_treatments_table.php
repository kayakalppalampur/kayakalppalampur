<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSpecialToPatientTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_tokens', function (Blueprint $table) {
            $table->integer('is_special')->nullable();
            $table->string('bp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_tokens', function (Blueprint $table) {
           $table->dropColumn('is_special');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToVitalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vital_data', function (Blueprint $table) {
            //
            $table->text('family_history')->nullable();
            $table->text('gynecological_obs_history')->nullable();
            $table->text('personal_history')->nullable();
            $table->string('diet')->nullable();
            $table->string('sleep')->nullable();
            $table->string('appetite')->nullable();
            $table->string('bowel')->nullable();
            $table->string('exercise')->nullable();
            $table->string('digestion')->nullable();
            $table->string('habits')->nullable();
            $table->string('urine')->nullable();
            $table->string('addiction')->nullable();
            $table->string('tongue')->nullable();
            $table->string('water_intake')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vital_data', function (Blueprint $table) {
            $table->dropColumn('family_history');
            $table->dropColumn('gynecological_obs_history');
            $table->dropColumn('personal_history');
            $table->dropColumn('diet');
            $table->dropColumn('sleep');
            $table->dropColumn('appetite');
            $table->dropColumn('bowel');
            $table->dropColumn('exercise');
            $table->dropColumn('digestion');
            $table->dropColumn('habits');
            $table->dropColumn('urine');
            $table->dropColumn('addiction');
            $table->dropColumn('tongue');
            $table->dropColumn('water_intake');
        });
    }
}


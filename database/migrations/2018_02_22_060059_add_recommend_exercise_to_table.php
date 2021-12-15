<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecommendExerciseToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discharge_patients', function (Blueprint $table) {
            $table->string('recommend_exercise')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discharge_patients', function (Blueprint $table) {
            //
            $table->dropColumn('recommend_exercise');


        });
    }
}

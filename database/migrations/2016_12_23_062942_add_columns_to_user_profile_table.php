<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->integer('patient_type')->comment('0 => IPD, 1 => OPD');
            $table->integer('age');
            $table->string('relative_name')->comment('S/o, D/o, W/o')->nullable();
            $table->integer('profession_id');
            $table->integer('marital_status')->comment('0 => UnMarried, 1 => Married')->nullable();
            $table->string('country_code');
            $table->string('landline_number')->nullable();
            $table->string('whatsapp_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->dropColumn('patient_type')->comment('0 => IPD, 1 => OPD');
            $table->dropColumn('age');
            $table->dropColumn('relative_name')->comment('S/o, D/o, W/o')->nullable();
            $table->dropColumn('profession_id');
            $table->dropColumn('marital_status')->comment('0 => UnMarried, 1 => Married')->nullable();
            $table->dropColumn('country_code');
            $table->dropColumn('landline_number')->nullable();
            $table->dropColumn('whatsapp_number')->nullable();
        });
    }
}

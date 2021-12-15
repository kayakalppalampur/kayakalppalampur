<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToPhysicalExaminations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->string('built')->nullable();
            $table->string('nourishment')->nullable();
            $table->string('temperature')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('icterus')->nullable();
            $table->string('cyanosis')->nullable();
            $table->string('clubbing')->nullable();
            $table->string('lymph_nodes_enlargement')->nullable();
            $table->string('oedema')->nullable();
            $table->string('tongue')->nullable();
        });

        Schema::table('ayurved_dhatu_examinations', function (Blueprint $table) {
            $table->string('vyadhi_ka_naam')->nullable();
        });

        Schema::table('patient_lab_tests', function (Blueprint $table) {
            $table->string('test_id')->change();
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
            $table->dropColumn('built');
            $table->dropColumn('nourishment');
            $table->dropColumn('temperature');
            $table->dropColumn('respiratory_rate');
            $table->dropColumn('icterus');
            $table->dropColumn('cyanosis');
            $table->dropColumn('clubbing');
            $table->dropColumn('lymph_nodes_enlargement');
            $table->dropColumn('oedema');
            $table->dropColumn('tongue');
        });
        Schema::table('ayurved_dhatu_examinations', function (Blueprint $table) {
            $table->dropColumn('vyadhi_ka_naam');
        });
    }
}

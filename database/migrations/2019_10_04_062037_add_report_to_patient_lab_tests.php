<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportToPatientLabTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_lab_tests', function (Blueprint $table) {
            if(!Schema::hasColumn('patient_lab_tests', 'lab_report')) {
                $table->string('lab_report')->nullable();
            }
            if(!Schema::hasColumn('patient_lab_tests', 'test_status')) {
                $table->integer('test_status')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_lab_tests', function (Blueprint $table) {
            if(Schema::hasColumn('patient_lab_tests', 'lab_report')) {
                $table->dropColumn('lab_report');
            }
            if(Schema::hasColumn('patient_lab_tests', 'lab_report')) {
               $table->integer('test_status');
            }
        });
    }
}

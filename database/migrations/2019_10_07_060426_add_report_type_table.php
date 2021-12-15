<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_lab_tests', function (Blueprint $table) {
            if(!Schema::hasColumn('patient_lab_tests', 'report_type')) {
                $table->string('report_type')->nullable();
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
            if(Schema::hasColumn('patient_lab_tests', 'report_type')) {
                $table->dropColumn('report_type');
            }
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceColumnPatientTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('treatments', function (Blueprint $table) {
            $table->float('price')->nullable();
        });
        Schema::table('patient_treatments', function (Blueprint $table) {
            $table->float('price')->nullable();
        });

        Schema::table('patient_lab_tests', function (Blueprint $table) {
            $table->float('price')->nullable();
        });

        Schema::table('booking_rooms', function (Blueprint $table) {
            $table->float('price')->nullable();
        });
        Schema::table('user_extra_services', function (Blueprint $table) {
            $table->float('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('patient_treatments', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('patient_lab_tests', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('booking_rooms', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('user_extra_services', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}

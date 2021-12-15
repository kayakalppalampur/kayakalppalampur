<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUserExternalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_extra_services', function (Blueprint $table) {
            $table->date('service_start_date')->nullable();
            $table->date('service_end_date')->nullable();
            $table->integer('member_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_extra_services', function (Blueprint $table) {
            $table->dropColumn('service_start_date');
            $table->dropColumn('service_end_date');
            $table->dropColumn('member_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserExtraServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_extra_services', function (Blueprint $table) {
            $table->integer('is_child_driver')->default(0)->comment = '0=>null,1=>child,2=>driver';
            $table->integer('service_id')->nullable()->change();

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
            $table->dropColumn('is_child_driver');
            $table->dropColumn('service_id');
        });
    }
}

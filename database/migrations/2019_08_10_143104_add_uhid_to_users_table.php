<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUhidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'registration_id')) {
                $table->dropColumn('registration_id');
            }

            $table->string('uhid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            if(!Schema::hasColumn('users', 'registration_id')) {
                $table->string('registration_id')->nullable();
            }

            $table->dropColumn('uhid');
        });
    }
}

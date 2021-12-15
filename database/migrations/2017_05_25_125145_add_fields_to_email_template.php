<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToEmailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_template', function (Blueprint $table) {
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->dateTime('sent_date_time')->nullable();
            $table->string('reply_to_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_template', function (Blueprint $table) {
            $table->dropColumn('from_email');
            $table->dropColumn('sent_date_time');
            $table->dropColumn('from_name');
            $table->dropColumn('reply_to_email');
        });
    }
}

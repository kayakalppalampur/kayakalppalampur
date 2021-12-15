<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalBankaccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_bankaccount', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name');
            $table->string('account_no');
            $table->date('date');
            $table->string('opening_balance');
            $table->integer('account_type');
            $table->string('branch');
            $table->integer('status')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_bankaccount');
    }
}

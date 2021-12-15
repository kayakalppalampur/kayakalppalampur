<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAyurvedAturExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayurved_atur_examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('created_by');
            $table->integer('status');
            $table->string('prakriti')->nullable();
            $table->string('saar')->nullable();
            $table->string('sanhanan')->nullable();
            $table->string('praman')->nullable();
            $table->string('satmyaya')->nullable();
            $table->string('ahaar_shakti')->nullable();
            $table->string('vyayaam_shakti')->nullable();
            $table->string('vaya')->nullable();
            $table->string('bal')->nullable();
            $table->string('drishya')->nullable();
            $table->string('prakriti_comment')->nullable();
            $table->string('saar_comment')->nullable();
            $table->string('varsh')->nullable();
            $table->string('uttpatti_desh')->nullable();
            $table->string('vyadhit_desh')->nullable();
            $table->string('chikitsa_desh')->nullable();
            $table->string('kaal')->nullable();
            $table->string('anal')->nullable();
            $table->string('rogi_awastha')->nullable();
            $table->string('rog_awastha')->nullable();
            $table->string('satva')->nullable();
            $table->string('rog_awastha')->nullable();
            $table->integer('booking_id')->nullable();
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
        Schema::dropIfExists('ayurved_atur_examinations');
    }
}

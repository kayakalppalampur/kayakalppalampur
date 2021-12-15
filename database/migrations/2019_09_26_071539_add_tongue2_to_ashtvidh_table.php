<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTongue2ToAshtvidhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ayurved_ashtvidh_examinations', function (Blueprint $table) {
            if(!Schema::hasColumn('ayurved_ashtvidh_examinations', 'tongue_2')) {
                $table->string('tongue_2')->nullable();
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
        Schema::table('ayurved_ashtvidh_examinations', function (Blueprint $table) {
             if(Schema::hasColumn('ayurved_ashtvidh_examinations', 'tongue_2')) {
                $table->dropColumn('tongue_2');
            }
        });
    }
}

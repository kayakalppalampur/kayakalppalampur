<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeFieldsInPhysiotherapyMotorExaminations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physiotherapy_motor_examinations', function (Blueprint $table) {
            $table->text('joint_id')->after('id')->nullable();
            $table->text('joint_sub_category_id')->after('joint_id')->nullable();
            $table->text('joint_right_side')->after('joint_sub_category_id')->nullable();
            $table->text('joint_left_side')->after('joint_right_side')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physiotherapy_motor_examinations', function (Blueprint $table) {
            $table->dropColumn('joint_id');
            $table->dropColumn('joint_sub_category_id');
            $table->dropColumn('joint_right_side');
            $table->dropColumn('joint_left_side');
        });
    }
}

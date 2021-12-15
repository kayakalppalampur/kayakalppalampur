<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_id');
            $table->string('profile_picture')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('office')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('about')->nullable();
            $table->string('location')->nullable();
            $table->string('facebook_url',255)->nullable();
            $table->string('twitter_url',255)->nullable();
            $table->string('linkedin_url',255)->nullable();
            $table->string('google_plus_url',255)->nullable();
            $table->string('youtube_url',255)->nullable();
            $table->string('pinterest_url',255)->nullable();
            $table->string('instagram_url',255)->nullable();
            $table->integer('booking_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_profiles');
    }
}

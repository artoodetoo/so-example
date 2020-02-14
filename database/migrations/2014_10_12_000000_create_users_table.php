<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->rememberToken();
            $table->string('profile_image_url', 512)->nullable();
            $table->string('website_url', 512)->nullable();
            $table->string('location', 191)->default('');
            $table->mediumText('about_me')->nullable();
            $table->integer('views')->default(0);
            $table->integer('reputation')->default(0);
            $table->integer('up_votes')->default(0);
            $table->integer('down_votes')->default(0);
            $table->unsignedBigInteger('account_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}

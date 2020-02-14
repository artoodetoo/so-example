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
            $table->string('name', 191)->comment('Display name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->rememberToken();
            $table->string('profile_image_url', 512)->nullable();
            $table->timestamp('last_accessed_at')->nullable()->comment('Datetime user last loaded a page; updated every 30 min at most');
            $table->string('website_url', 512)->nullable();
            $table->string('location', 191)->default('');
            $table->mediumText('about_me')->nullable();
            $table->integer('views')->default(0)->comment('Number of times the profile is viewed');
            $table->integer('reputation')->default(0);
            $table->integer('up_votes')->default(0)->comment('How many upvotes the user has cast');
            $table->integer('down_votes')->default(0);
            $table->unsignedBigInteger('account_id')->nullable()->comment('User\'s Stack Exchange Network profile ID');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('badge_name', 191)->comment('Name of the badge');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('badge_class')->default(3)->comment('1 = Gold; 2 = Silver; 3 = Bronze');
            $table->tinyInteger('tag_based')->default(0)->comment('1 if badge is for a tag, otherwise it is a named badge');
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
        Schema::dropIfExists('badges');
    }
}

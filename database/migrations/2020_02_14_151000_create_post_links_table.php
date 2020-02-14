<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id')->comment('id of source post');
            $table->unsignedBigInteger('related_post_id')->comment('id of target/related post');
            $table->tinyInteger('link_type_id')->nullable()->comment('1 = Linked (PostId contains a link to RelatedPostId); ' .
                    '3 = Duplicate (PostId is a duplicate of RelatedPostId)');
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
        Schema::dropIfExists('post_links');
    }
}

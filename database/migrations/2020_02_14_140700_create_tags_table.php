<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tag_name', 191);
            $table->integer('tag_count')->nullable();
            $table->unsignedBigInteger('excerpt_post_id')->nullable()->comment('Id of Post that holds the excerpt text of the tag');
            $table->unsignedBigInteger('wiki_post_id')->nullable()->comment('Id of Post that holds the wiki text of the tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}

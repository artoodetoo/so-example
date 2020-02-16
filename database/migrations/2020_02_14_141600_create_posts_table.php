<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 191)->nullable();
            $table->mediumText('body')->nullable()->comment('as rendered HTML, not Markdown');
            $table->string('tag_list', 512)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('only present if post_type_id = 2');
            $table->unsignedBigInteger('owner_id')->nullable()->comment('');
            $table->string('owner_name', 191)->nullable()->comment('only present if user has not been deleted; ' .
                                        'always -1 for tag wiki entries, i.e. the community user owns them');
            $table->unsignedBigInteger('editor_id')->nullable()->comment('');
            $table->string('editor_name', 191)->nullable();
            $table->tinyInteger('post_type_id')->comment('1 = Question; 2 = Answer; 3 = Orphaned tag wiki; ' .
                                        '4 = Tag wiki excerpt; 5 = Tag wiki; 6 = Moderator nomination; ' .
                                        '7 = Wiki placeholder; 8 = Privilege wiki');
            $table->unsignedBigInteger('accepted_id')->nullable()->comment('only present if post_type_id = 1');
            $table->integer('score')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('answer_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->timestamps();
            $table->timestamp('activity_at')->nullable()->comment('datetime of the post\'s most recent activity');
            $table->timestamp('closed_at')->nullable()->comment('present only if the post is closed');
            $table->timestamp('owned_at')->nullable()->comment('present only if post is community wiki\'d');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

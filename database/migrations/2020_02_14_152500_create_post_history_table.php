<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('history_type_id')->comment(
                '1 = Initial Title; ' .
                '2 = Initial Body; ' .
                '3 = Initial Tags; ' .
                '4 = Edit Title; ' .
                '5 = Edit Body; ' .
                '6 = Edit Tags; ' .
                '7 = Rollback Title; ' .
                '8 = Rollback Body; ' .
                '9 = Rollback Tags; ' .
                '10 = Post Closed; ' .
                '11 = Post Reopened ; ' .
                '12 = Post Deleted; ' .
                '13 = Post Undeleted ; ' .
                '14 = Post Locked; ' .
                '15 = Post Unlocked; ' .
                '16 = Community Owned; ' .
                '17 = Post Migrated; ' .
                '18 = Question Merged; ' .
                '19 = Question Protected; ' .
                '20 = Question Unprotected; ' .
                '21 = Post Disassociated; ' .
                '22 = Question Unmerged; ' .
                '24 = Suggested Edit Applied; ' .
                '25 = Post Tweeted; ' .
                '31 = Comment discussion moved to chat; ' .
                '33 = Post notice added; ' .
                '34 = Post notice removed; ' .
                '35 = Post migrated away; ' .
                '36 = Post migrated here; ' .
                '37 = Post merge source; ' .
                '38 = Post merge destination; ' .
                '50 = Bumped by Community User; ' .
                '52 = Question became hot network question; ' .
                '53 = Question removed from hot network questions by a moderator');
            $table->unsignedBigInteger('post_id');
            $table->char('revision_guid', 16)->collation('binary')->comment('At times more than one type of history record can be recorded by a single action. ' .
                'All of these will be grouped using the same RevisionGUID');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 191)->nullable()->comment('populated if a user has been removed and no longer referenced by user Id');
            $table->string('comment', 191)->nullable()->comment('the comment made by the user who edited a post. ' .
                'If history_type_id = 10, this field contains the close_reason_id');
            $table->mediumText('body')->nullable()->comment(
                'A raw version of the new value for a given revision ' .
                '- If PostHistoryTypeId in (10,11,12,13,14,15,19,20,35) this column will contain a JSON encoded string with all users who have voted for the PostHistoryTypeId ' .
                '- If it is a duplicate close vote, the JSON string will contain an array of original questions as OriginalQuestionIds ' .
                '- If PostHistoryTypeId = 17 this column will contain migration details of either from <url> or to <url>');
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
        Schema::dropIfExists('post_history');
    }
}

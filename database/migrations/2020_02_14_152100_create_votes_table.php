<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->tinyInteger('vote_type_id')->comment(
                    '1 = AcceptedByOriginator; ' .
                    '2 = UpMod (AKA upvote); ' .
                    '3 = DownMod (AKA downvote); ' .
                    '4 = Offensive; ' .
                    '5 = Favorite (UserId will also be populated); ' .
                    '6 = Close (effective 2013-06-25: Close votes are only stored in table: PostHistory); ' .
                    '7 = Reopen; ' .
                    '8 = BountyStart (UserId and BountyAmount will also be populated); ' .
                    '9 = BountyClose (BountyAmount will also be populated); ' .
                    '10 = Deletion; ' .
                    '11 = Undeletion; ' .
                    '12 = Spam; ' .
                    '15 = ModeratorReview; ' .
                    '16 = ApproveEditSuggestion');
            $table->unsignedBigInteger('user_id')->nullable()->comment('present only if VoteTypeId in (5,8); 1 if user is deleted');
            $table->integer('bounty_amount')->nullable()->comment('present only if VoteTypeId in (8,9)');
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
        Schema::dropIfExists('votes');
    }
}

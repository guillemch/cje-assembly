<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesVolumnToSecretVoteRollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('secret_vote_roll', function (Blueprint $table) {
            $table->integer('votes')->after('secret_vote_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('secret_vote_roll', function (Blueprint $table) {
            $table->dropColumn('votes');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecretVoteBallotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secret_vote_ballots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('secret_vote_option_id')->unsigned();
            $table->timestamps();

            $table->foreign('secret_vote_option_id')->references('id')->on('secret_vote_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secret_vote_ballots');
    }
}

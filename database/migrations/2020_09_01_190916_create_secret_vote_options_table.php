<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecretVoteOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secret_vote_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('secret_vote_id')->unsigned();
            $table->string('name');

            $table->foreign('secret_vote_id')->references('id')->on('secret_votes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secret_vote_options');
    }
}

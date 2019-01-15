<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('amendment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->enum('vote_for', ['1', '2', '3', '4', '5']);
            $table->ipAddress('ip');
            $table->text('user_agent');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('amendment_id')->references('id')->on('amendments');
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

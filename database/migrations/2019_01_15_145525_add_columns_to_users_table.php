<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['1', '2'])->after('id');
            $table->integer('group_id')->unsigned()->nullable()->after('type');
            $table->string('phone')->after('email');
            $table->enum('role', ['admin', 'vote_manager', 'credential_manager', 'attendant'])->default('attendant')->after('password');

            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'group_id', 'phone', 'role']);
            $table->dropIndex(['group_id']);
        });
    }
}

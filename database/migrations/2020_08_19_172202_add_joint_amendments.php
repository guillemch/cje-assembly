<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJointAmendments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amendments', function (Blueprint $table) {
            $table->integer('joint_with')->nullable()->after('option_5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amendments', function (Blueprint $table) {
            $table->dropColumn('joint_with');
        });
    }
}

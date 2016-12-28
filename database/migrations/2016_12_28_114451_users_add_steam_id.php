<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddSteamId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('steam_id')->unsigned()->nullable();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            $table->index('steam_id', 'users_steam_id_index');
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
            $table->string('email')->change();
            $table->string('password')->change();

            $table->dropIndex('users_steam_id_index');
            $table->dropColumn('steam_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GamesAddUploadedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recorded_games', function (Blueprint $table) {
            $table->integer('uploader_id')->unsigned()->nullable();

            $table->foreign('uploader_id')->references('id')->on('users');
        });
        Schema::table('game_sets', function (Blueprint $table) {
            $table->integer('author_id')->unsigned()->nullable();

            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recorded_games', function (Blueprint $table) {
            $table->dropColumn('uploader_id');
        });
        Schema::table('game_sets', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
}

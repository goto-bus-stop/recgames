<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordedGamePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorded_game_players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recorded_game_analysis_id')->unsigned();
            $table->string('name');
            $table->tinyInteger('player_index');
            $table->enum('type', ['human', 'ai', 'spectator']);
            $table->tinyInteger('team');
            $table->tinyInteger('color');
            $table->integer('civilization');
            $table->integer('rating')->nullable();

            $table->foreign('recorded_game_analysis_id')
                  ->references('id')->on('recorded_game_analyses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

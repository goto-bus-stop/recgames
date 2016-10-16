<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordedGameAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorded_game_analyses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recorded_game_id')->unsigned();
            $table->integer('version');
            $table->integer('duration');
            $table->integer('game_type');
            $table->boolean('multiplayer');
            $table->integer('map_size');
            $table->integer('map_id');
            $table->integer('pop_limit');
            $table->boolean('lock_diplomacy');
            $table->timestamps();

            $table->foreign('recorded_game_id')
                  ->references('id')->on('recorded_games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recorded_game_analyses');
    }
}

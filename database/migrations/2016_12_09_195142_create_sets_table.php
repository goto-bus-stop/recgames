<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('game_sets_games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('set_id')->unsigned();
            $table->integer('game_id')->unsigned();

            $table->foreign('set_id')->references('id')->on('game_sets');
            $table->foreign('game_id')->references('id')->on('recorded_games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_sets_games');
        Schema::drop('game_sets');
    }
}

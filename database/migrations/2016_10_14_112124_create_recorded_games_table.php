<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordedGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorded_games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('filename');
            $table->string('path')->unique();
            $table->enum('status', ['queued', 'processing', 'completed', 'errored'])
                  ->default('queued');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recorded_games');
    }
}

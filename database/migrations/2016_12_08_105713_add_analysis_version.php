<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnalysisVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recorded_game_analyses', function (Blueprint $table) {
            $table->integer('analysis_version')->unsigned()->default(0);
            $table->renameColumn('version', 'game_version');
            $table->float('game_subversion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recorded_game_analyses', function (Blueprint $table) {
            $table->dropColumn(['analysis_version', 'game_subversion']);
            $table->renameColumn('game_version', 'version');
        });
    }
}

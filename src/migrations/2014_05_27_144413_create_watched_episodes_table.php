<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchedEpisodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watched_episodes', function (Blueprint $table)
        {
            $table->integer('episode_id')
                  ->unsigned();
            $table->integer('user_id')
                  ->unsigned();
            $table->tinyInteger('status')
                  ->default(0);

            $table->index('episode_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('watched_episodes');
    }

}

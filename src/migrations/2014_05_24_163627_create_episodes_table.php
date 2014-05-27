<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table)
        {
            $table->increments('id');

            $table->string('name', 50);
            $table->text('overview');
            $table->dateTime('first_aired')
                  ->nullable();

            $table->integer('tv_show_id')
                  ->unsigned();
            $table->integer('season_id')
                  ->unsigned();

            $table->integer('season_number')
                  ->unsigned();
            $table->integer('episode_number')
                  ->unsigned();

            $table->timestamps();

            $table->index('name');
            $table->index('tv_show_id');
            $table->index('season_id');
            $table->index('season_number');
            $table->index('episode_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('episodes');
    }

}

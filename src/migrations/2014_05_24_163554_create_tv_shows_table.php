<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_shows', function(Blueprint $table) {
            $table->increments('id');

            $table->string('name', 50);
            $table->text('overview');
            $table->string('network', 30);
            $table->dateTime('first_aired')->nullable();

            $table->integer('thetvdb_id')->unsigned();
            $table->string('imdb_id', 30)->nullable();

            $table->timestamps();

            $table->index('name');
            $table->index('thetvdb_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tv_shows');
    }
}

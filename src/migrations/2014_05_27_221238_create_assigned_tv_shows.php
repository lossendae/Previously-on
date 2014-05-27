<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignedTvShows extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_tv_shows', function (Blueprint $table)
        {
            $table->integer('tv_show_id')
                  ->unsigned()
                  ->index();
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('assigned_tv_shows');
    }

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('id_movie');
            $table->string('movie_name');
            $table->text('description')->nullable();
            $table->integer('duration');
            $table->date('release_date');
            $table->string('country');
            $table->string('producer');
            $table->string('genre');
            $table->string('director');
            $table->text('cast');
            $table->string('poster_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}

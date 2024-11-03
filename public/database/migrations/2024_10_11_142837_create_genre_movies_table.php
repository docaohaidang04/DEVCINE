<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('genre_movies', function (Blueprint $table) {
            $table->id('id_genre');
            $table->string('genre_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_movies');
    }
}

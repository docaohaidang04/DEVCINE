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
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->date('release_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('country')->nullable();
            $table->string('producer')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->string('image_main')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('poster_url')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('ID_MOVIE');
            $table->string('Movie_name');
            $table->decimal('Price', 10, 2);
            $table->text('Description')->nullable();
            $table->integer('Duration')->nullable();
            $table->date('Release_date')->nullable();
            $table->string('Country')->nullable();
            $table->string('Producer')->nullable();
            $table->string('Director')->nullable();
            $table->text('Cast')->nullable();
            $table->string('Poster_Url')->nullable();
            $table->foreignId('ID_GENRE')->constrained('genre_movies', 'ID_GENRE')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}

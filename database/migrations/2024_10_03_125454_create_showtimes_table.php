<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowtimesTable extends Migration
{
    public function up()
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id('id_showtime');
            $table->unsignedBigInteger('id_movie');
            $table->unsignedBigInteger('id_room');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('id_movie')->references('id_movie')->on('movies');
            $table->foreign('id_room')->references('id_room')->on('rooms');
        });
    }

    public function down()
    {
        Schema::dropIfExists('showtimes');
    }
}

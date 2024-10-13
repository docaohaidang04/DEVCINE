<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowtimesTable extends Migration
{
    public function up()
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id('ID_SHOWTIME');
            $table->foreignId('ID_MOVIE')->constrained('movies', 'ID_MOVIE');
            $table->foreignId('ID_ROOM')->constrained('rooms', 'ID_ROOM');
            $table->dateTime('Start_time');
            $table->dateTime('End_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('showtimes');
    }
}

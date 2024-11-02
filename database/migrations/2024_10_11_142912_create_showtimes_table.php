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
            $table->foreignId('id_movie')->constrained('movies', 'id_movie');
            $table->foreignId('id_room')->constrained('rooms', 'id_room');
            $table->dateTime('date_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('showtimes');
    }
}

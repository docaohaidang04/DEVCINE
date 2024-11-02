<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowtimeSlotShowtimeTable extends Migration
{
    public function up()
    {
        Schema::create('showtime_slot_showtime', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_showtime')->constrained('showtimes', 'id_showtime')->onDelete('cascade');
            $table->foreignId('id_slot')->constrained('showtime_slots', 'id_slot')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('showtime_slot_showtime');
    }
}
